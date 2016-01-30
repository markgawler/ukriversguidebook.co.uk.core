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
			JText::_('COM_UKRGB_SUBMENU_EVENTS_MANAGER'),
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
			$vName == 'calendar'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @return	JObject
	 */
	/*public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId)) {
			$assetName = 'com_ukrgb';
		} else {
			$assetName = 'com_ukrgb.category.'.(int) $categoryId;
		}

		$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}*/
}