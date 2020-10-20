# Decompose your Laravel route files

[![Latest Stable Version](https://poser.pugx.org/soysaltan/larasplitter/v)](//packagist.org/packages/soysaltan/larasplitter) [![Total Downloads](https://poser.pugx.org/soysaltan/larasplitter/downloads)](//packagist.org/packages/soysaltan/larasplitter) [![Latest Unstable Version](https://poser.pugx.org/soysaltan/larasplitter/v/unstable)](//packagist.org/packages/soysaltan/larasplitter) [![License](https://poser.pugx.org/soysaltan/larasplitter/license)](//packagist.org/packages/soysaltan/larasplitter)

In some cases, you have thought of separating your Laravel api files, in this case you will need to make some changes in the project. This job can often become a chore, I have often faced this problem so I developed a package to help you automate this kind of chore.

## Getting Started

### 1. Install

Run the following command:

```bash
composer require soysaltan/laravel-api-splitter
```

### 2. Register (for Laravel < 5.5)

Register the service provider in `config/app.php`

```php
 Soysaltan\LaraSplit\ApiSplitterServiceProvider::class,
```

### 3. Publish

Publish config file.

```bash
php artisan vendor:publish --tag=splitter
```


### 4. Configure

You can change the currencies information of your app from `config/splitter.php` file

## Usage

```php
php artisan spl:it
```
After this command, you will be asked two questions.
- Please enter a api file name (the filename will be saved with '.api' suffix): foo
- Please enter an endpoint name: foos

Finally, you will get a message like
  - You can find your '**SplitApiFooServiceProvider**' class at '**/path/to/app/Providers'**
  - Your '**foo.api.php**' file has located at '**/path/to/api/routes**'

### How it works
**Soysaltan\ApiSplitter\ApiSplitterServiceProvider** registers the api file you want to separate under the **app/Providers** folder. This register dynamically does the following:

```php
 foreach (glob(base_path('app/Providers/SplitApi*')) as $file) {
		$className = basename($file, '.php');
		$this->app->register("\App\Providers\\$className");
	}
```
Each separate api file is mapped to a provider and registered in the system in this way.

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email soysaltann@gmail.com instead of using the issue tracker.

## Credits

- [Soysal Tan](https://github.com/paramientos)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
