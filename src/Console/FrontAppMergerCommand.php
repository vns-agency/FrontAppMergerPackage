<?php

namespace VnsAgency\FrontAppMerger\Console;

use App\Merger\ProcessType;
use Illuminate\Console\Command;
use VnsAgency\FrontAppMerger\Processor;
use FrontAppMerger;
class FrontAppMergerCommand extends Command
{
    protected $signature = 'frontAppMerger';

    protected $description = 'Start the front apps merger.';

    public function handle()
    {
        $this->info('Start Front Apps Merger...');

        foreach (FrontAppMerger::getApps() as $app) {
            if($app['processType'] === ProcessType::GIT) {
                $this->info('Start cloning the repository ' . $app['repository']);
            }else{
                $this->info('Start processing local app ' . $app['repository']);
            }

            (new Processor(
                repository: $app['repository'],
                processType: $app['processType'],
                projectType: $app['projectType'],
                replaceIndexViewPath: $app['replaceIndexViewPath'],
            ))->run();
        }
    }
}
