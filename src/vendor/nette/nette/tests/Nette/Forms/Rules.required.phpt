<?php

/**
 * Test: Nette\Forms\Rules.
 */

use Nette\Forms\Form,
	Nette\Forms\Rule,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';


test(function() { // BaseControl
	$form = new Form;
	$input = $form->addText('text');

	Assert::false( $input->isRequired() );
	Assert::same( $input, $input->setRequired() );
	Assert::true( $input->isRequired() );
});


test(function() { // Rules
	$form = new Form;
	$input = $form->addText('text');
	$rules = $input->getRules();

	Assert::false( $rules->isRequired() );
	Assert::same( $rules, $rules->setRequired() );
	Assert::true( $rules->isRequired() );

	$items = iterator_to_array($rules);
	Assert::same( 1, count($items) );
	Assert::same( Form::REQUIRED, $items[0]->operation );
	Assert::same( Rule::VALIDATOR, $items[0]->type );
	Assert::false( $items[0]->isNegative );

	Assert::false( $rules->validate() );
	Assert::same( array('This field is required.'), $input->getErrors() );
});


test(function() { // 'required' is always the first rule
	$form = new Form;
	$input = $form->addText('text');
	$rules = $input->getRules();

	$rules->addRule($form::EMAIL);
	$rules->addRule($form::REQUIRED);

	$items = iterator_to_array($rules);
	Assert::same( 2, count($items) );
	Assert::same( Form::REQUIRED, $items[0]->operation );
	Assert::same( Form::EMAIL, $items[1]->operation );

	$rules->addRule(~$form::REQUIRED);
	$items = iterator_to_array($rules);
	Assert::same( 2, count($items) );
	Assert::same( Form::REQUIRED, $items[0]->operation );
	Assert::true( $items[0]->isNegative );
	Assert::same( Form::EMAIL, $items[1]->operation );

	Assert::false( $rules->validate() );
	Assert::same( array('Please enter a valid email address.'), $input->getErrors() );
});


test(function() { // setRequired(FALSE)
	$form = new Form;
	$input = $form->addText('text');
	$rules = $input->getRules();

	$rules->addRule($form::EMAIL);
	$rules->addRule($form::REQUIRED);
	$rules->setRequired(FALSE);

	$items = iterator_to_array($rules);
	Assert::same( 1, count($items) );
	Assert::same( Form::EMAIL, $items[0]->operation );

	Assert::false( $rules->validate() );
	Assert::same( array('Please enter a valid email address.'), $input->getErrors() );
});


test(function () { // addRule(~Form::REQUIRED)
	$form = new Form;
	$input = $form->addText('text');

	Assert::false( $input->isRequired() );
	Assert::same( $input, $input->addRule(~Form::REQUIRED) );
	Assert::false( $input->isRequired() );
});
