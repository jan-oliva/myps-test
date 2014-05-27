<?php

namespace controllers;

use Silex\Application;

class MethodsController
{

    public function renderList(Application $app)
    {
        $broker = new \TokenReflection\Broker(new \TokenReflection\Broker\Backend\Memory());
        $broker->processDirectory('../src/app', array('*Controller.php'));

        $methods = $statistics = array();
        $classes = $broker->getClasses();
        /* @var $class \TokenReflection\Php\ReflectionClass */
        foreach ($classes as $class) {
            if (!$class->isSubclassOf('controllers\BaseController')) {
                continue;
            }

            $className = $class->getName();
            /* @var $method TokenReflection\Php\ReflectionMethod */
            foreach ($class->getOwnMethods() as $method) {
                $methodName = array();
                if (preg_match('@^(?:render|before|after|event)(.+)$@', $method->getName(), $methodName)) {
                    $methods[$className][$methodName[1]][] = $methodName[0];
                    $statistics[$methodName[1]]++;
                }
            }
        }

        $statisticsByCount = $statistics;
        $statisticsByName = $statistics;

        arsort($statisticsByCount);
        ksort($statisticsByName);

        return $app['twig']->render('methods/list.html.twig', array(
                'methods' => $methods,
                'statisticsByCount' => $statisticsByCount,
                'statisticsByName' => $statisticsByName
        ));
    }
}
