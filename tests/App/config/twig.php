<?php

/* only work if you installed twig config
composer require dune/twig-support
*/
return [

  // twig files path
  'twig_path' => PATH.'/app/views',

  // twig debug
  'debug' => false,

  // twig cache path
  'cache' => PATH.'/storage/cache/twig',

  // twig auti reload
  'auto_reload' => true,

  //twig strict variables
  'strict_variables' => false

  ];
