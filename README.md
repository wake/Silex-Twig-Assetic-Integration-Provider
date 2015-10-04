# Twig Assetic Integration Provider

This library is an integration service of Twig and Assetic works for Silex.

The original twig extension of Assetic is not easy to use. There's a lot of work (code) to do and you need always to know which templates require assets.

## Registering

```php
$app->register (new Silex\Provider\TwigAsseticIntegrationProvider (), array (
  'assetic.asset.root'        => 'your_asset_files_root',
  'assetic.asset.output_root' => 'your_asset_output_root',
  'assetic.asset.debug'       => false
));
```

Important: You need to register Twig service provider before this code. We override some Twig loader to make it provide more template informations.

## Usage

Juse like Assetic TwigExtension

```php
{% stylesheets '/path/to/sass/main.sass' filter='sass,?yui_css' output='css/all.css' %}
    <link href="{{ asset_url }}" type="text/css" rel="stylesheet" />
{% endstylesheets %}
```