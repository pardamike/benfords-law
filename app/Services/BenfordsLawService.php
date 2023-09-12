<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BenfordsLawService
{
    public function __construct(
        public array $distribution, 
        public int $variance,
        public int $minLength,
    ) {}

    public function analyzeForBenfordsLaw(array $integers): array
    {
        $returnData = [
            'success' => true,
            'summary' => 'SUCCESS: This data set follows Benfords Law!',
            'allowedVariance' => $this->variance,
            'data' => array_fill(1, 9, [
                'n' => 0,
                'occurances' => 0,
                'percent' => 0.0,
                'benford' => null,
                'withinRange' => 'No',
            ]),
        ];

        if ($error = $this->inputHasError($integers)) {
            $returnData['success'] = false;
            $returnData['summary'] = $error;
            return $returnData;
        }

        $occuranceFrequencyCounts = $this->occuranceFrequencyCount($integers);
        $occuranceFrequencyPercent = $this->occuranceFrequencyPercent($occuranceFrequencyCounts, count($integers));

        foreach ($returnData['data'] as $k => &$data) {
            $data['benford'] = $this->distribution[$k];
            $data['n'] = $k;
            if ($occuranceFrequencyCounts[$k] > 0) {
                $data['occurances'] = $occuranceFrequencyCounts[$k];
                $data['percent'] = number_format((float) $occuranceFrequencyPercent[$k], 2, '.', '');
                $data['withinRange'] = ($occuranceFrequencyPercent[$k] <= $this->distribution[$k] + $this->variance) && 
                                       ($occuranceFrequencyPercent[$k] >= $this->distribution[$k] - $this->variance) ? 
                                       'Yes' : 'No';
            }

            if ($occuranceFrequencyCounts[$k] === 0 || $data['withinRange'] === 'No') {
                $returnData['summary'] = "FAIL: Distribution of one or more numbers fall below Benfords Law - using variance threshold of {$this->variance}%";
            }
        }

        return $returnData;
    }

    public function occuranceFrequencyPercent(array $occuranceCounts, int $totalInts): array
    {
        $leadingNumberOccurancePercent = array_fill(1, 9, 0.0);
        foreach ($occuranceCounts as $k => $count) {
            $leadingNumberOccurancePercent[$k] = ($count / $totalInts) * 100;
        }
        return $leadingNumberOccurancePercent;
    }

    public function occuranceFrequencyCount(array $integers): array
    {
        $leadingNumberOccurance = array_fill(1, 9, 0);
        foreach ($integers as $k => $int) {
            if ($leadingNum = (int) substr($int, 0, 1)) {
                $leadingNumberOccurance[$leadingNum] += 1;
            }
        }
        return $leadingNumberOccurance;
    }

    public function inputHasError(array $integers): string|bool
    {
        if (count($integers) < $this->minLength) {
            return sprintf('Too small of data set to test, please input at least %s integers', $this->minLength);
        }

        foreach ($integers as $num) {
            if (!is_int($num) || $num < 0) {
                return 'Please ensure all items are positive and are integers';
            }
        }

        return false;
    }
}