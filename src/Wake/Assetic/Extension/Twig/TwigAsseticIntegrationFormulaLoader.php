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

use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;
use Psr\Log\LoggerInterface;

use Assetic\Extension\Twig\AsseticNode;
use Assetic\Extension\Twig\AsseticFilterFunction;

/**
 * Loads asset formulae from Twig templates.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class TwigAsseticIntegrationFormulaLoader implements FormulaLoaderInterface
{
    private $app;
    private $twig;
    private $logger;

    public function __construct(\Twig_Environment $twig, LoggerInterface $logger = null, $app = null)
    {
        $this->twig   = $twig;
        $this->logger = $logger;
        $this->app    = $app;
    }

    public function load(ResourceInterface $resource)
    {
        try {
            $tokens = $this->twig->tokenize($resource->getContent(), (string) $resource);
            $nodes  = $this->twig->parse($tokens);
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error(sprintf('The template "%s" contains an error: %s', $resource, $e->getMessage()));
            }

            return array();
        }

        return $this->loadNode($nodes);
    }

    /**
     * Loads assets from the supplied node.
     *
     * @param \Twig_Node $node
     *
     * @return array An array of asset formulae indexed by name
     */
    private function loadNode(\Twig_Node $node)
    {
        $formulae = array();

        if ($node instanceof AsseticNode) {
            $formulae[$node->getAttribute('name')] = array(
                $node->getAttribute('inputs'),
                $node->getAttribute('filters'),
                array(
                    'output'  => $node->getAttribute('asset')->getTargetPath(),
                    'name'    => $node->getAttribute('name'),
                    'debug'   => $node->getAttribute('debug'),
                    'combine' => $node->getAttribute('combine'),
                    'vars'    => $node->getAttribute('vars'),
                ),
            );
        } elseif ($node instanceof \Twig_Node_Expression_Function) {
            $name = $node->getAttribute('name');

            if ($this->twig->getFunction($name) instanceof AsseticFilterFunction) {
                $arguments = array();
                foreach ($node->getNode('arguments') as $argument) {
                    $arguments[] = eval('return '.$this->twig->compile($argument).';');
                }

                $invoker = $this->twig->getExtension('assetic')->getFilterInvoker($name);

                $inputs  = isset($arguments[0]) ? (array) $arguments[0] : array();
                $filters = $invoker->getFilters();
                $options = array_replace($invoker->getOptions(), isset($arguments[1]) ? $arguments[1] : array());

                if (!isset($options['name'])) {
                    $options['name'] = $invoker->getFactory()->generateAssetName($inputs, $filters, $options);
                }

                $formulae[$options['name']] = array($inputs, $filters, $options);
            }
        }

        foreach ($node as $child) {
            if ($child instanceof \Twig_Node) {
                $formulae += $this->loadNode($child);
            }
        }

        if ($node->hasAttribute('embedded_templates')) {
            foreach ($node->getAttribute('embedded_templates') as $child) {
                $formulae += $this->loadNode($child);
            }
        }

        $formulaes = array ();
        $asseticDebug = $this->app['assetic.debug'];

        foreach ($formulae as $n => $f) {

            list ($inputs, $filters, $options) = $f;

            // 當沒有資源或此資源已被解開過的時候
            if (count($inputs) > 0 && strlen ($n) <= 7

                && ($asseticDebug == true || $options['debug'] === true || $options['combine'] === false)) {


                $path = pathinfo ($options['output']);
                $counter = 1;

                foreach ($inputs as $input) {

                    $inputPath = pathinfo ($input);

                    $_name    = $n . '_' . md5 ($input);
                    $_inputs  = array ($input);
                    $_filters = $filters;
                    $_options = array (
                        'output' => "{$path['dirname']}/{$path['filename']}_{$inputPath['filename']}_{$counter}.{$path['extension']}",
                        'name'   => "{$path['filename']}_{$inputPath['filename']}_{$counter}",
                        ) + $options;

                    $formulaes[$_name] = array ($_inputs, $_filters, $_options);

                    $counter ++;
                }
            }

            else {
                $formulaes[$n] = $f;
            }
        }

        $formulae = $formulaes;

        return $formulae;
    }
}
