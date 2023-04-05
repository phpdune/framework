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
            return "Controller Name Not Passed" . PHP_EOL;
        }
        //checks controller already exists
        if (file_exists(PATH . "/app/controllers/" . $name . ".php")) {
            return "Controller Already Exists" . PHP_EOL;
        }
        //getting stub
        $stub = $this->getStubController($name);
        //creating file
        ($file = fopen("app/controllers/{$name}.php", "w")) or
            die("Unable to open file!");
        fwrite($file, $stub);
        fclose($file);
        return $name . " Created Successfully" . PHP_EOL;
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
}
