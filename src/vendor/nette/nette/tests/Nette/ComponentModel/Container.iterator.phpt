<?php

/**
 * Test: Nette\ComponentModel\Container iterator.
 */

use Nette\ComponentModel\Component,
	Nette\ComponentModel\Container,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


class Button extends Component
{
}

class ComponentX extends Component
{
}

$c = new Container(NULL, 'top');

$c->addComponent(new Container, 'one');
$c->addComponent(new ComponentX, 'two');
$c->addComponent(new Button, 'button1');

$c->getComponent('one')->addComponent(new ComponentX, 'inner');
$c->getComponent('one')->addComponent(new Container, 'inner2');
$c->getComponent('one')->getComponent('inner2')->addComponent(new Button, 'button2');


// Normal
$list = $c->getComponents();
Assert::same( array(
	"one",
	"two",
	"button1",
), array_keys(iterator_to_array($list)) );


// Filter
$list = $c->getComponents(FALSE, 'Button');
Assert::same( array(
	"button1",
), array_keys(iterator_to_array($list)) );


// RecursiveIteratorIterator
$list = new RecursiveIteratorIterator($c->getComponents(), 1);
Assert::same( array(
	"one",
	"inner",
	"inner2",
	"button2",
	"two",
	"button1",
), array_keys(iterator_to_array($list)) );


// Recursive
$list = $c->getComponents(TRUE);
Assert::same( array(
	"one",
	"inner",
	"inner2",
	"button2",
	"two",
	"button1",
), array_keys(iterator_to_array($list)) );


// Recursive CHILD_FIRST
$list = $c->getComponents(-1);
Assert::same( array(
	"inner",
	"button2",
	"inner2",
	"one",
	"two",
	"button1",
), array_keys(iterator_to_array($list)) );


// Recursive & filter I
$list = $c->getComponents(TRUE, 'Button');
Assert::same( array(
	"button2",
	"button1",
), array_keys(iterator_to_array($list)) );


// Recursive & filter II
$list = $c->getComponents(TRUE, 'Nette\ComponentModel\Container');
Assert::same( array(
	"one",
	"inner2",
), array_keys(iterator_to_array($list)) );
