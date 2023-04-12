<?php

declare(strict_types=1);

namespace Dune; 

use Dotenv\Dotenv;

final class App 
{
     /**
      * load the env variables and set custom error handling
      * 
      * @param  none
      *
      * @return none
      */
  public function __construct()
  {
    $dotenv = Dotenv::createImmutable(PATH);
    $dotenv->load();
    set_error_handler('errorHandler', E_ALL);
    set_exception_handler('exceptionHandler');
    
  }
     /**
      * load apo configuration
      * 
      * @param  none
      *
      * @return none
      */
  public function load(): void
  {
    $this->loadAppConfig();
    require PATH.'/routes/web.php';
    echo runRoutes();
  }
     /**
      * load apo configuration
      * 
      * @param  none
      *
      * @return none
      */  
  public function loadAppConfig(): void
  {
    date_default_timezone_set(config('app.timezone'));
  }

}