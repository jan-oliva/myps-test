<?php

/**
 * Test: Nette\Forms default rendering with IE fix.
 */

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$_SERVER['REQUEST_METHOD'] = 'POST';

$form = new Nette\Forms\Form;
$form->addHidden('userid');
$form->addSubmit('submit', 'Send');

$form->fireEvents();

Assert::match( '<form action="" method="post">

<table>
<tr>
	<th></th>

	<td><input type="submit" name="_submit" class="button" id="frm-submit" value="Send"></td>
</tr>
</table>

<div><input type="hidden" name="userid" id="frm-userid" value=""><!--[if IE]><input type=IEbug disabled style="display:none"><![endif]--></div>
</form>', $form->__toString(TRUE) );
