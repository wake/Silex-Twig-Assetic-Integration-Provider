<?php

  namespace Wake;

  /**
   *
   * @author Wake Liu <wake.gs@gmail.com>
   */
  class Twig_AsseticLoader_Filesystem extends \Twig_Loader_Filesystem implements Twig_AsseticLoaderInterface {

    /**
     * Get loader instances.
     *
     * @return array The array of Twig_LoaderInterface instances
     */
    public function getLoader () {
      return array ($this);
    }

    /**
     * Get filename and path of template
     *
     * @return array Template file name and path
     */
    public function getTemplate () {
      return $this->cache;
    }
  }
