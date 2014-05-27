<?php

require_once __DIR__ . '/silex/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/silex/views',
));

$app->get('/', function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

$app->get('/build/run/', 'controllers\BuildController::renderRun');
$app->get('/build/list/', 'controllers\BuildController::renderList');
$app->get('/build/{dir}/delete/', 'controllers\BuildController::renderDelete');
$app->get('/build/{dir}/reports/', 'controllers\BuildController::renderReports');
$app->get('/build/{dir}/reports/{file}.html', 'controllers\BuildController::renderReport');

$app->get('/methods/list/', 'controllers\MethodsController::renderList');

$app->get('/composer/update/', 'controllers\ComposerController::renderUpdate');
$app->get('/composer/install/', 'controllers\ComposerController::renderInstall');
$app->get('/composer/delete-vendor/', 'controllers\ComposerController::renderDeleteVendor');

$app->get('/phpunit/run/', 'controllers\PhpunitController::renderRun');

$app->get('/fixer/run/', 'controllers\FixerController::renderRun');
$app->get('/fixer/dry-run/', 'controllers\FixerController::renderDryRun');

$app->run();