<?php

namespace controllers;

use Silex\Application;

class ComposerController extends BaseController
{

    public function renderUpdate(Application $app)
    {
        return $this->execCommand($app, 'sudo -n -u vagrant composer --prefer-source --no-interaction --no-ansi --no-progress --profile update -v 2>&1');
    }

    public function renderInstall(Application $app)
    {
        return $this->execCommand($app, 'sudo -n -u vagrant composer --prefer-source --no-interaction --no-ansi --no-progress --profile install -v 2>&1');
    }

    public function renderDeleteVendor(Application $app)
    {
        return $this->execCommand($app, 'sudo -n -u vagrant rm -rf src/vendor/ 2>&1');
    }
}
