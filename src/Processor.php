<?php

namespace VnsAgency\FrontAppMerger;

use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use VnsAgency\FrontAppMerger\Enums\PackageManagerType;
use VnsAgency\FrontAppMerger\Enums\ProcessType;
use VnsAgency\FrontAppMerger\Enums\ProjectType;

class Processor
{
    private string $appFolderPath;
    public function __construct(
        private readonly string             $repository,
        private readonly ?string            $replaceIndexViewPath = null,
        private readonly ProcessType        $processType = ProcessType::GIT,
        private readonly ProjectType        $projectType = ProjectType::VUE,
        private readonly PackageManagerType $packageManagerType = PackageManagerType::Yarn,
        private readonly bool               $copyAssets = true,
        private readonly string             $distFolderName = 'dist',
        private ?string                     $copyDistFilesTo = null,
    ){
        $name = str(last(explode('/', $repository)))->replace('.git', '')->prepend('fontApps'.DIRECTORY_SEPARATOR);
        $this->appFolderPath = base_path($name);
        if(is_null($this->copyDistFilesTo)) $this->copyDistFilesTo = public_path();
    }

    public function run()
    {
        $this->makeDirs();
        set_error_handler(fn () => null);
        $result = Process::pipe(function (Pipe $pipe) {
            match ($this->processType){
                ProcessType::GIT => $this->processFromGit($pipe),
                ProcessType::Local => $this->processFromLocal($pipe),
            };

            $this->processBuild($pipe);
        }, function (string $type, string $output) {
            echo $output;
        });

        if ($result->successful()) {
            restore_error_handler();

            //copy index.html content
            $indexContent = File::get($this->appFolderPath . DIRECTORY_SEPARATOR . $this->distFolderName . DIRECTORY_SEPARATOR .'index.html');
            //delete index.html, so it does not affect index in public
            File::delete($this->appFolderPath . DIRECTORY_SEPARATOR . $this->distFolderName . DIRECTORY_SEPARATOR .'index.html');
            // start copping assets
            if($this->copyAssets){
                File::copyDirectory($this->appFolderPath. DIRECTORY_SEPARATOR .$this->distFolderName, $this->copyDistFilesTo);
                echo "Assets copied successfully.\n";
            }
            // put index.html content to blade file
            if($this->replaceIndexViewPath) {
                File::replace($this->replaceIndexViewPath, $indexContent);
                echo "Replaces blade file with index content successfully.\n";
            }
        }
    }

    private function processFromGit(Pipe $pipe): void
    {
        $pipe->path(base_path('fontApps'))->command('git clone '. $this->repository);
    }

    private function processFromLocal(Pipe $pipe): void
    {}

    private function processBuild(Pipe $pipe): void
    {
        match ($this->projectType){
            ProjectType::VUE => $this->vueHandler($pipe),
        };
    }

    private function getRepositoryPath(): string
    {
        return base_path($this->repository);
    }

    private function vueHandler(Pipe $pipe): void
    {
        $pipe->path($this->appFolderPath)->timeout(200)->command(
            $this->packageManagerType === PackageManagerType::Yarn ?
                "cd $this->appFolderPath && yarn"
                :"cd $this->appFolderPath && npm install"
        );
        $pipe->path($this->appFolderPath)->timeout(200)->command($this->getRepositoryPath())->command(
            $this->packageManagerType === PackageManagerType::Yarn ?
                "cd $this->appFolderPath && yarn build"
                :"cd $this->appFolderPath && npm run build"
        );;
    }

    private function makeDirs()
    {
        File::ensureDirectoryExists(base_path('fontApps'), 0777);
        File::ensureDirectoryExists($this->appFolderPath, 0777);
    }


    public function clearUp()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            function rrmdir($dir) {
                if (is_dir($dir)) {
                    $objects = scandir($dir);
                    foreach ($objects as $object) {
                        if ($object != "." && $object != "..") {
                            if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                                rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                            else
                                unlink($dir. DIRECTORY_SEPARATOR .$object);
                        }
                    }
                    rmdir($dir);
                }
            }
            rrmdir(base_path('fontApps'));
        } else {
            Process::path(base_path())->run('rm -rf fontApps', function ($t, $o){
                echo $o;
            });
        }
    }

}
