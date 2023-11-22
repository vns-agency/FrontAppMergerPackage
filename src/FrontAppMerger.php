<?php

namespace VnsAgency\FrontAppMerger;


use VnsAgency\FrontAppMerger\Enums\ProcessType;
use VnsAgency\FrontAppMerger\Enums\ProjectType;

class FrontAppMerger
{
    private array $apps = [];

    public function registerGitRepo(
        string $repository,
        ProjectType $projectType = ProjectType::VUE,
        string $replaceIndexViewPath = null
    ) : static
    {
        $this->apps[] = [
            'repository' => $repository,
            'processType' => ProcessType::GIT,
            'projectType' => $projectType,
            'replaceIndexViewPath' => $replaceIndexViewPath,
        ];

        return $this;
    }

    public function registerLocalRepo(
        string $path,
        ProjectType $projectType = ProjectType::VUE,
        string $replaceIndexViewPath = null
    ) : static
    {
        $this->app[] = [
            'repository' => $path,
            'processType' => ProcessType::Local,
            'projectType' => $projectType,
            'replaceIndexViewPath' => $replaceIndexViewPath,
        ];

        return $this;
    }

    public function getApps()
    {
        return $this->apps;
    }

    public function cleanUp()
    {
        foreach ($this->apps as $app){
            (new Processor(
                repository: $app['repository'],
                replaceIndexViewPath: $app['replaceIndexViewPath'],
                processType: $app['processType'],
                projectType: $app['projectType'],
            ))->clearUp();
        }
    }

    public function run()
    {
        foreach ($this->apps as $app){
            new Processor(
                repository: $app['repository'],
                replaceIndexViewPath: $app['replaceIndexViewPath'],
                processType: $app['processType'],
                projectType: $app['projectType'],
            );
        }
    }
}
