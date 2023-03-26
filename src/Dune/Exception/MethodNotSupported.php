<?php
//global exception
namespace Dune\Exception;

class MethodNotSupported extends \Exception
{
    protected $message = 'Method Not Supported For This Route';
}
