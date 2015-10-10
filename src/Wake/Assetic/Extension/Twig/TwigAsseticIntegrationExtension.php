<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2014 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wake\Assetic\Extension\Twig;

use Assetic\Extension\Twig\AsseticExtension;
use Assetic\Factory\AssetFactory;
use Assetic\ValueSupplierInterface;

class TwigAsseticIntegrationExtension extends AsseticExtension
{
    protected $app;

    public function __construct(AssetFactory $factory, $functions = array(), ValueSupplierInterface $valueSupplier = null, $app = null)
    {
        parent::__construct ($factory, $functions, $valueSupplier);

        $this->app = $app;
    }

    public function getTokenParsers()
    {
        return array(
            new TwigAsseticIntegrationTokenParser($this->factory, 'javascripts', 'js/*.js', false, array (), $this->app),
            new TwigAsseticIntegrationTokenParser($this->factory, 'stylesheets', 'css/*.css', false, array (), $this->app),
            new TwigAsseticIntegrationTokenParser($this->factory, 'image', 'images/*', true, array (), $this->app),
        );
    }
}
