# AppSero Client Updater
### Version 1.0.0

- [Depency](#depency)
- [Installation](#installation)
- [Usages](#usages)

## Depenct

At first you need to isntall [Appseo Client](https://github.com/Appsero/client/blob/develop/readme.md)


## Installation

You can install AppSero Client Updater in two ways, via composer and manually.

### 1. Composer Installation

Add dependency in your project (theme/plugin):

```
composer require appsero/updater
```

Now add `autoload.php` in your file if you haven't done already.

```php
require __DIR__ . '/vendor/autoload.php';
```


### 2. Manual Installation

Clone the repository in your project.

```bash
cd /path/to/your/project/folder
git clone https://github.com/AppSero/updater.git updater
```

##### Now include the dependencies in your plugin/theme.

```php
if (! class_exists('Appsero\Updater')) {
  require __DIR__ . '/updater/src/Updater.php';
}
```

## Usages

```php
$client = new Appsero\Client( 'a4a8da5b-b419-4656-98e9-4a42e9044891', 'Akismet', __FILE__ );

// Active automatic updater
Appsero\Updater::init($client);
```
