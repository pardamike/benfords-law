<?php

namespace Tests\Unit;

use Tests\Helpers\Data\BenfordsLawHelperData;
use Tests\TestCase;

class BenfordsLawServiceTest extends TestCase
{
    protected $benfordService;

    public function setUp(): void
    {
        parent::setUp();
        $this->benfordService = $this->app->make('App\Services\BenfordsLawService');
    }

    public function test_analyze_for_benfords_law(): void
    {
        $result = $this->benfordService->analyzeForBenfordsLaw([4,5,6,8,2,6,3]);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Too small of data set to test', $result['summary']);

        $result = $this->benfordService->analyzeForBenfordsLaw([4,5,1,1,6,3,8,-8,2,6,3,6,9.45,2,1,1]);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Please ensure all items are positive and are integers', $result['summary']);

        $result = $this->benfordService->analyzeForBenfordsLaw(BenfordsLawHelperData::WILL_NOT_PASS);
        $this->assertStringContainsString('FAIL: Distribution of or more numbers fall below Benfords Law', $result['summary']);

        $result = $this->benfordService->analyzeForBenfordsLaw(BenfordsLawHelperData::WILL_PASS);
        $this->assertStringContainsString('SUCCESS: This data set follows Benfords Law!', $result['summary']);
    }
    
    public function test_occurance_frequency_percent(): void
    {
        $result = $this->benfordService->occuranceFrequencyPercent([
            1 => 3,
            2 => 1,
            3 => 1,
            4 => 2,
            5 => 4,
            6 => 1,
            7 => 1,
            8 => 0,
            9 => 2,
        ], 16);

        $this->assertEquals(18.75, $result[1]);
        $this->assertEquals(12.5, $result[4]);
        $this->assertEquals(25, $result[5]);
        $this->assertEquals(12.5, $result[9]);
    }

    public function test_occurance_frequency_count(): void
    {
        $result = $this->benfordService->occuranceFrequencyCount([1,1,1,2,3,4,5,5,5,5,6,7,8,9,9,9,9,9,9]);
        $this->assertEquals(3, $result[1]);
        $this->assertEquals(4, $result[5]);
        $this->assertEquals(6, $result[9]);
    }

    public function test_input_validations(): void
    {
        $error = $this->benfordService->inputHasError([1,2,3]);
        $this->assertStringContainsString('Too small of data set to test', $error);

        $error = $this->benfordService->inputHasError([1,2,3,4,5,6,7,-8,9,1,2,3,4,5,6,7,8,9]);
        $this->assertStringContainsString('Please ensure all items are positive and are integers', $error);

        $error = $this->benfordService->inputHasError([1,2,3.5,4,5,6,7,8.54,9,1,2,3,4,5,6,7.777,8,9]);
        $this->assertStringContainsString('Please ensure all items are positive and are integers', $error);

        $error = $this->benfordService->inputHasError([1,2,3,4,5,6,7,8,9,1,2,3,4,5,6,7,8,9]);
        $this->assertFalse($error);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
