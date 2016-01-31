<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) 2013 - 2014 Mark Gawler, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Ukrgb helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 *
 */
class UkrgbHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName = 'eventmanager')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_UKRGB_SUBMENU_EVENTS'),
			'index.php?option=com_ukrgb&view=eventmanager',
			$vName == 'eventmanager'
		);
				
		JHtmlSidebar::addEntry(
			JText::_('COM_UKRGB_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_ukrgb',
			$vName == 'categories'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_UKRGB_SUBMENU_CALENDAR'),
			'index.php?option=com_ukrgb&view=calendarmanager',
			$vName == 'calendarmanager'
		);
	
		JHtmlSidebar::addEntry(
			JText::_('COM_UKRGB_SUBMENU_SKILLS'),
			'index.php?option=com_ukrgb&view=skillmanager',
			$vName == 'skillmanager'
		);
	}
}