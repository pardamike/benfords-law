<?php

namespace App\Console\Commands;

use App\Services\BenfordsLawService;
use Illuminate\Console\Command;

class BenfordsLawCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'benfords-law:run 
                            {integers* : space or comma separated list of integers to test against}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a list of integers (separated by spaces or commas) and tests them against Benfords Law.\n
                              Output is a small table and the result.\n
                              Example Input: benfords-law:run 1 3 5 7 9\n
                              See README for more details and assumptions';
    /**
     * Execute the console command.
     */
    public function handle(BenfordsLawService $benfordService): int
    {
        $integersToTest = $this->argument('integers');

        if (count($integersToTest) === 1 && str_contains($integersToTest[0], ',')) {
            $integersToTest = explode(',', $integersToTest[0]);
        }

        $integersToTest = array_map('intval', $integersToTest);
        $result = $benfordService->analyzeForBenfordsLaw($integersToTest);

        if (!$result['success']) {
            $this->error("ERROR: {$result['summary']}");
            return 1;
        }

        $this->info("Testing input for Benfords Law... \n");
        $this->info($result['summary']);
        $tableHeaders = [' n ', 'Freq', 'Pct', 'Benford Pct', "Within Variance (+/-{$benfordService->variance}%)"];
        $this->table($tableHeaders, $result['data']);
        return 0;
    }
}
