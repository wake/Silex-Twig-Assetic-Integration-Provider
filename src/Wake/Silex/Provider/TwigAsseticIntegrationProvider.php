<?php

  namespace Wake\Silex\Provider;

  use Silex\Application;
  use Silex\ServiceProviderInterface;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;

  use Assetic\AssetWriter;
  use Assetic\Extension\Twig\AsseticExtension;
  use Assetic\Extension\Twig\TwigFormulaLoader;
  use Assetic\Extension\Twig\TwigResource;
  use Assetic\Factory\AssetFactory;
  use Assetic\Factory\LazyAssetManager;
  use Assetic\FilterManager;
  use Assetic\AssetManager;


  /**
   * Integration assetic service with Twig
   *
   * @author Wake Liu <wake.gs@gmail.com>
   */
  class TwigAsseticIntegrationProvider implements ServiceProviderInterface {

    /**
     *
     * @param  Application $app
     */
    function register (Application $app) {

      $app['assetic.asset.root'] = '';
      $app['assetic.asset.output_root'] = '';
      $app['assetic.debug'] = false;
      $app['assetic.filters'] = array (
        'csscopyfile' => true,
        'lessphp'     => true,
        'scssphp'     => true,
        'cssmin'      => true,
        'csscompress' => true,
        'jsmin'       => true,
      );

      $app['assetic.filter_manager'] = $app->share (function ($app) {
        $filterManager = new FilterManager ();
        return $filterManager;
      });

      $app['assetic.asset_manager'] = $app->share (function ($app) {
        $assetManager = new AssetManager ();
        return $assetManager;
      });

      $app['assetic.asset_factory'] = $app->share (function ($app) {
        $assetFactory = new AssetFactory ($app['assetic.asset.root']);
        $assetFactory->setDefaultOutput ($app['assetic.asset.output_root']);
        $assetFactory->setFilterManager ($app['assetic.filter_manager']);
        $assetFactory->setAssetManager ($app['assetic.asset_manager']);
        $assetFactory->setDebug ($app['assetic.debug']);
        return $assetFactory;
      });

      $app['assetic.lazy_asset_manager'] = $app->share (function ($app) {
        $lazyAssetManager = new LazyAssetManager ($app['assetic.asset_factory']);
        $lazyAssetManager->setLoader ('twig', new TwigFormulaLoader ($app['twig']));
        return $lazyAssetManager;
      });

      $app['assetic.asset_writer'] = $app->share (function ($app) {
        $assetWriter = new AssetWriter ($app['assetic.asset.output_root']);
        return $assetWriter;
      });


      /*
       * Twig
       *
       */

      // Replace Twig loader
      $app['twig.loader.filesystem'] = $app->share (function ($app) {
        return new \Wake\Twig_AsseticLoader_Filesystem ($app['twig.path']);
      });

      $app['twig.loader'] = $app->share (function ($app) {
        return new \Wake\Twig_AsseticLoader_Chain (array(
          $app['twig.loader.array'],
          $app['twig.loader.filesystem'],
        ));
      });
    }


    /**
     *
     * @param  Application $app
     */
    function boot (Application $app) {

      $app['twig']->addExtension (new AsseticExtension ($app['assetic.asset_factory']));

      $app->after (function (Request $request, Response $response) use ($app) {

        $templates = $app['twig.loader']->getTemplate ();

        foreach ($templates as $filename => $fullpath) {
          $resource = new TwigResource ($app['twig']->getLoader (), $filename);
          $app['assetic.lazy_asset_manager']->addResource ($resource, 'twig');
        }

        $app['assetic.asset_writer']->writeManagerAssets ($app['assetic.lazy_asset_manager']);

      });
    }
  }
