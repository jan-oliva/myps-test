<?php

namespace controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PhpunitController extends BaseController
{

    public function renderRun(Application $app, Request $req)
    {
        $path = $req->get('path');
        $command = sprintf('sudo -i -n -u vagrant bash -c "cd project/tests && phpunit -v %s 2>&1"', escapeshellarg($path));
        return $this->execCommand($app, $command);
    }
}
