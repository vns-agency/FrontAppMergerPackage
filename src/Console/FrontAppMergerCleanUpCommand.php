<?php

namespace VnsAgency\FrontAppMerger\Console;

use App\Merger\ProcessType;
use Illuminate\Console\Command;
use VnsAgency\FrontAppMerger\Processor;
use FrontAppMerger;
class FrontAppMergerCleanUpCommand extends Command
{
    protected $signature = 'frontAppMerger:cleanUp';

    protected $description = 'Start the front apps merger.';

    public function handle()
    {
        $this->info('Start Cleaning Up the Front Apps...');

        FrontAppMerger::cleanUp();

        $this->info('End the cleaning process.');
    }
}
