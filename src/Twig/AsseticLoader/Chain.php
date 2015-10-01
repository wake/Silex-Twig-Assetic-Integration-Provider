<?php


  /**
   *
   * @author Wake Liu <wake.gs@gmail.com>
   */
  class Twig_AsseticLoader_Chain extends Twig_Loader_Chain implements Twig_AsseticLoaderInterface {

    /**
     * Get loader instances.
     *
     * @return array The array of Twig_LoaderInterface instances
     */
    public function getLoader () {
      return $this->loaders;
    }

    /**
     *
     * @return array Template file name and path
     */
    public function getTemplate () {

      $templates = array ();

      foreach ($this->loaders as $loader) {
        if (is_object ($loader) && $loader instanceof Twig_AsseticLoaderInterface) {
          $templates += $loader->getTemplate ();
        }
      }

      return $templates;
    }
  }
