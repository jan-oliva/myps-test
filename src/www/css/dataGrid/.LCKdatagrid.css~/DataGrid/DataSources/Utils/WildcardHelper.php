<?php

namespace PM\DataGrid\DataSources\Utils;
use Nette\Utils\Strings as String;
class WildcardHelper
{
	/**
	 * Format given value for LIKE statement
	 *
	 * @param string $value
	 * @param string $replacement
	 * @return string
	 */
	public static function formatLikeStatementWildcards($value, $replacement = '%')
	{
		// Escape wildcard character used in PDO
		$value = str_replace($replacement, '\\' . $replacement, $value);

		// Replace asterisks
		$value = String::replace($value, '~(?!\\\\)(.?)\\*~', '\\1' . $replacement);

		// Replace escaped asterisks
		return str_replace('\\*', '*', $value);
	}
}