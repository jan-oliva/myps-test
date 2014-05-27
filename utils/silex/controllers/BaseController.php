<?php

namespace controllers;

use Silex\Application;

class BaseController
{

    public function execCommand(Application $app, $command)
    {
        $output = $return = '';
        $command = sprintf($command);

        chdir('../');
        exec($command, $output, $return);

        return $app->json(array(
                'exitcode' => $return,
                'output' => '<strong>' . $command . '</strong>' . '<br>' . implode('<br>', $output),
        ));
    }
}
