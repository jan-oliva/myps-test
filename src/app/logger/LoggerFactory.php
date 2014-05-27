<?php



/**
 * Description of LoggerFactory
 *
 * @author Jan Oliva
 */
class LoggerFactory
{
	public function createLogger()
	{
		$logger = new \Nette\Diagnostics\Logger();
	}
}
