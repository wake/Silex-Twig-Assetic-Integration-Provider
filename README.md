# Twig Assetic Integration Provider

This is an integration library of [Twig](http://twig.sensiolabs.org) and [Assetic](https://github.com/kriswallsmith/assetic) works for [Silex](http://silex.sensiolabs.org/).

The original [Twig extension of Assetic](https://github.com/kriswallsmith/assetic#twig) is not easy to use. There's a lot of work (code) to do and you need always to know which templates require assets.

This integration library will handle it automatically.

## Installation

Add in your `composer.json` with following require entry:

```json
{
  "require": {
    "wake/Twig-Assetic-Integration-Provider": "*"
  }
}
```

or using composer:

```bash
$ composer require wake/Twig-Assetic-Integration-Provider:*
```

then run `composer install` or `composer update`.

## Registering


```php
$app->register (new Wake\Silex\Provider\TwigAsseticIntegrationProvider (), array (
  'assetic.asset.root'        => 'your_asset_files_root',
  'assetic.asset.output_root' => 'your_asset_output_root',
  'assetic.debug'             => false,
  'assetic.filter'            => array (
    'sass'    => new \Assetic\Filter\SassFilter ('/path/to/parser/sass'),
    'yui_css' => new \Assetic\Filter\Yui\CssCompressorFilter ('/path/to/yuicompressor.jar')
  )
));
```

Important: Make sure you are using [TwigServiceProvider](http://silex.sensiolabs.org/doc/providers/twig.html) and have registered before this code. It override some Twig loaders to force it expode some template information.

## Usage

Just as [Assetic twig extension](https://github.com/kriswallsmith/assetic#twig)

```html
{% stylesheets '/path/to/sass/main.sass' filter='sass,?yui_css' output='css/all.css' %}
    <link href="{{ asset_url }}" type="text/css" rel="stylesheet" />
{% endstylesheets %}
```

## Filter Auto Detecting

Twig Assetic Integration Provider provides [Assetic filters](https://github.com/kriswallsmith/assetic#filters) auto detecting as below list:

### CssMinFilter

- Description: Minifies CSS
- Filter name: `cssmin`
- Requirement: [natxet/CssMin](https://github.com/natxet/CssMin)

### CssRewriteFilter

- Description: Fixes relative URLs in CSS assets when moving to a new URL
- Filter name: `cssrewrite`

## Feedback

Please feel free to open an issue and let me know if there is any thoughts or questions :smiley:

## License

Released under the MIT license