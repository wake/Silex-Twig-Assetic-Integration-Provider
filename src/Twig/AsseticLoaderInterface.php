<?php


  /**
   * 
   * @author Wake Liu <wake.gs@gmail.com>
   */
  interface Twig_AsseticLoaderInterface {

    /**
     * Get loader instances.
     *
     * @return array The array of Twig_LoaderInterface instances
     */
    public function getLoader ();


    /**
     * Get filename and path of template
     *
     * @return array Template file name and path
     */
    public function getTemplate ();
  }
