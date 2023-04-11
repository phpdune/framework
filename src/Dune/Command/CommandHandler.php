<?php

declare(strict_types=1);

namespace Dune\Command;

class CommandHandler
{
    /**
     * creating controller method
     *
     * @param ?string $name
     *
     * @return ?string
     */
    protected function createController(?string $name): ?string
    {
        //checks controller name is passed
        if (is_null($name)) {
            return "\033[31m" . "Controller Name Not Passed" . PHP_EOL . "\033[0m";
        }
        //checks controller already exists
        if (file_exists(PATH . "/app/controllers/" . $name . ".php")) {
            return "\033[31m" . "Controller Already Exists" . PHP_EOL . "\033[0m";
        }
        //getting stub
        $stub = $this->getStubController($name);
        //creating file
        ($file = fopen("app/controllers/{$name}.php", "w")) or
            die("Unable to open file!");
        fwrite($file, $stub);
        fclose($file);
        return "\033[32m" . $name . " Created Successfully" . PHP_EOL . "\033[0m";
    }
    /**
     * creating middleware method
     *
     * @param ?string $name
     *
     * @return ?string
     */
    protected function createMiddleware(?string $name): ?string
    {
        //checks controller name is passed
        if (is_null($name)) {
            return "\033[31m" . "Middleware Name Not Passed" . PHP_EOL . "\033[0m";
        }
        //checks controller already exists
        if (file_exists(PATH . "/app/middleware/" . $name . ".php")) {
            return "\033[31m" . "Middleware Already Exists" . PHP_EOL . "\033[0m";
        }
        //getting stub
        $stub = $this->getStubMiddleware($name);
        //creating file
        ($file = fopen("app/middleware/{$name}.php", "w")) or
            die("Unable to open file!");
        fwrite($file, $stub);
        fclose($file);
        return "\033[32m" . $name . " Created Successfully" . PHP_EOL . "\033[0m";
    }
    /**
     * creating request method
     *
     * @param ?string $name
     *
     * @return ?string
     */
    protected function createRequest(?string $name): ?string
    {
        //checks controller name is passed
        if (is_null($name)) {
            return "\033[31m" . "Request Name Not Passed" . PHP_EOL . "\033[0m";
        }
        //checks controller already exists
        if (file_exists(PATH . "/app/request/" . $name . ".php")) {
            return "\033[31m" . "Request Already Exists" . PHP_EOL . "\033[0m";
        }
        //getting stub
        $stub = $this->getStubRequest($name);
        //creating file
        if(!file_exists(PATH.'/app/request')){
          mkdir(PATH.'/app/request');
        }
        ($file = fopen("app/request/{$name}.php", "w")) or
            die("Unable to open file!");
        fwrite($file, $stub);
        fclose($file);
        return "\033[32m" . $name . " Created Successfully" . PHP_EOL . "\033[0m";
    }
    /**
     * return the controller stub file
     *
     * @param string $name
     *
     * @return string
     */
    private function getStubController(string $name): string
    {
        $stub = PATH . "/vendor/dune/framework/src/Dune/Stubs/controller.stub";
        $stub = file_get_contents($stub);
        $stub = str_replace("{{ Controller }}", $name, $stub);
        return $stub;
    }
    /**
     * return the middleware stub file
     *
     * @param string $name
     *
     * @return string
     */
    private function getStubMiddleware(string $name): string
    {
        $stub = PATH . "/vendor/dune/framework/src/Dune/Stubs/middleware.stub";
        $stub = file_get_contents($stub);
        $stub = str_replace("{{ Middleware }}", $name, $stub);
        return $stub;
    }
    /**
     * return the request stub file
     *
     * @param string $name
     *
     * @return string
     */
    private function getStubRequest(string $name): string
    {
        $stub = PATH . "/vendor/dune/framework/src/Dune/Stubs/request.stub";
        $stub = file_get_contents($stub);
        $stub = str_replace("{{ Request }}", $name, $stub);
        return $stub;
    }
}
