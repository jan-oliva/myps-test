<?php
// source: /home/vagrant/.composer/vendor/apigen/apigen/templates/default/overview.latte

// prolog Latte\Macros\CoreMacros
list($_l, $_g) = $template->initialize('1338907074', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lbed53cb8af3_title')) { function _lbed53cb8af3_title($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;echo Latte\Runtime\Filters::escapeHtml($config->title ?: 'Overview', ENT_NOQUOTES) ;
}}

//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb64364eb603_content')) { function _lb64364eb603_content($_l, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><div id="content">
	<h1><?php call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars()) ?></h1>

<?php $group = false ?>

<?php if ($namespaces) { ob_start() ?>
	<table class="summary" id="namespaces">
	<caption>Namespaces summary</caption>
<?php $iterations = 0; foreach ($namespaces as $namespace) { if ($config->main && 0 !== strpos($namespace, $config->main)) continue ?>
	<tr>
<?php $group = true ?>
		<td class="name"><a href="<?php echo htmlSpecialChars(Latte\Runtime\Filters::safeUrl($template->namespaceUrl($namespace))) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($namespace, ENT_NOQUOTES) ?></a></td>
	</tr>
<?php $iterations++; } ?>
	</table>
<?php if ($iterations) ob_end_flush(); else ob_end_clean(); } ?>

<?php if ($packages) { ob_start() ?>
	<table class="summary" id="packages">
	<caption>Packages summary</caption>
<?php $iterations = 0; foreach ($packages as $package) { if ($config->main && 0 !== strpos($package, $config->main)) continue ?>
	<tr>
<?php $group = true ?>
		<td class="name"><a href="<?php echo htmlSpecialChars(Latte\Runtime\Filters::safeUrl($template->packageUrl($package))) ?>
"><?php echo Latte\Runtime\Filters::escapeHtml($package, ENT_NOQUOTES) ?></a></td>
	</tr>
<?php $iterations++; } ?>
	</table>
<?php if ($iterations) ob_end_flush(); else ob_end_clean(); } ?>

<?php if (!$group) { $_l->templates['1338907074']->renderChildTemplate('@elementlist.latte', $template->getParameters()) ;} ?>
</div>
<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = '@layout.latte'; $template->_extended = $_extended = TRUE;

if ($_l->extends) { ob_start();}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
 $active = 'overview' ?>

<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars())  ?>


<?php call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 