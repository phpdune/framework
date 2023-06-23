<?php

namespace Dune\Tests\Unit\Routing\Controller;

use Dune\Routing\Controller;
use Dune\Http\Request;

class TestController implements Controller
{
    public function test()
    {
        return 'controller test';
    }
    public function show()
    {
        return 'dune test';
    }
    public function testdi($id, $token)
    {
        return 'success';
    }
    public function testdi2(Request $req, $id)
    {
        return 'ok';
    }
}
