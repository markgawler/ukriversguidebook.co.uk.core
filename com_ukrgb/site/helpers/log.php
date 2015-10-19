<?php
/*
 * @package UKRGB
 * @author Mark Gawler
 * @copyright (C) 2015 Mark Gawler
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class UkrgbLogger {

	const LogCategory = 'ukrgb';

	function __construct($log_level)
	{
		$priorities = JLog::ALL;
		JLog::addLogger(array(
			'text_file' => 'com_ukrgb.log.php',
			'text_entry_format' => '{DATETIME} {PRIORITY} {MESSAGE}'
		),
		$priorities,
		array(self::LogCategory));
	}


	function log($message, $priority)
	{
		jimport('joomla.application.component.helper');

		if (JComponentHelper::getParams('com_ukrgb')->get('debug')){
			error_log($message);
			JLog::add($message, $priority, self::LogCategory);
			
			JFactory::getApplication()->enqueueMessage($message,'notice');
		}
	}
}
