<?php

namespace controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class FixerController extends BaseController
{

    public function renderRun(Application $app, Request $req)
    {
        $path = $req->get('path');
        $command = sprintf('sudo -i -n -u vagrant bash -c "cd project && php-cs-fixer fix %s -vvv -n --no-ansi 2>&1"', escapeshellarg($path));
        return $this->execCommand($app, $command);
    }

    public function renderDryRun(Application $app, Request $req)
    {
        $path = $req->get('path');
        $command = sprintf('sudo -i -n -u vagrant bash -c "cd project && php-cs-fixer fix %s --dry-run -vvv -n --no-ansi 2>&1"', escapeshellarg($path));
        return $this->execCommand($app, $command);
    }
}
