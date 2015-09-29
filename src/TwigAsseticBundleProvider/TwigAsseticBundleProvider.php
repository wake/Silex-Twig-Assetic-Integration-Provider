<?php

  namespace wake\TwigAsseticBundleProvider;

  use Silex\Application;
  use Silex\ServiceProviderInterface;
  use Silex\ControllerProviderInterface;

  use Assetic\AssetWriter;
  use Assetic\Extension\Twig\AsseticExtension;
  use Assetic\Extension\Twig\TwigFormulaLoader;
  use Assetic\Factory\AssetFactory;
  use Assetic\Factory\LazyAssetManager;
  use Assetic\Filter\CssMinFilter;
  use Assetic\Filter\JSMinFilter;
  use Assetic\Filter\LessphpFilter;
  use Assetic\Filter\MinifyCssCompressorFilter;
  use Assetic\Filter\ScssphpFilter;
  use Assetic\FilterManager;


  /**
   * Loads asset formulae from Twig templates.
   *
   * @author Kris Wallsmith <kris.wallsmith@gmail.com>
   */
  class TwigAsseticBundleProvider implements ServiceProviderInterface {


    /**
     * @brief
     *
     *
     *
     * @public
     * @param
     * @return
     */
    function register (Application $app) {
    }


    /**
     * @brief
     *
     *
     *
     * @public
     * @param
     * @return
     */
    function boot (Application $app) {
    }
  }
