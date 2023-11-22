# front-app-merger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vns-agency/front-app-merger.svg?style=flat-square)](https://packagist.org/packages/vns-agency/front-app-merger)
[![Total Downloads](https://img.shields.io/packagist/dt/vns-agency/front-app-merger.svg?style=flat-square)](https://packagist.org/packages/vns-agency/front-app-merger)
![GitHub Actions](https://github.com/vns-agency/front-app-merger/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require vns-agency/front-app-merger
```

## Usage

```php
// in you AppServiceProvider
use FrontAppMerger;


public function boot(): void
{
    if ($this->app->runningInConsole())
    {
        FrontAppMerger::registerGitRepo(
            repository: "git@github.com:vns-agency/YMP-website.git",
            //the blade file path that should be replaced with the newly generated index.html
            replaceIndexViewPath: resource_path('views/index.blade.php'),// defult null

            //optional parameters and its default values
            processType: ProcessType::GIT, //or ProcessType::Local
            //the front-end app framework
            projectType: ProjectType::VUE, //currently only supported VUE
            //package manager Yarn or NPM
            packageManagerType: PackageManagerType::Yarn, //defult value
            //disable copping Assets
            copyAssets: true, 
            //set the front-end app build folder 
            distFolderName: 'dist',
            //where should the assets move to 
            copyDistFilesTo: public_path()
        )
    }
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email test@test.com instead of using the issue tracker.

## Credits

-   [Mahmoud Yas](https://github.com/binyaas)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
