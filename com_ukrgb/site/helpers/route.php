<?php

/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */


defined('_JEXEC') or die;

/**
 * UKRGB Component Route Helper.
 *
 */
abstract class UkrgbHelperRoute
{
	protected static $lookup = array();

	/**
	 * Get the event route.
	 *
	 * @param   integer  $id        The route of the content item.
	 * @param   integer  $catid     The category ID.
	 * @param   integer  $language  The language code.
	 *
	 * @return  string  The event route.
	 *
	 */
	public static function getEventRoute($id, $catid = 0, $language = 0)
	{
		$needles = array(
			'event'  => array((int) $id)
		);

		// Create the link
		$link = 'index.php?option=com_ukrgb&view=event&id=' . $id;

		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$link .= '&lang=' . $language;
			$needles['language'] = $language;
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Get the calendar route.
	 *
	 * @param   integer  $id        The route of the content item.
	 * @param   integer  $catid     The category ID.
	 * @param   integer  $language  The language code.
	 *
	 * @return  string  The event route.
	 *
	 */
	public static function getCalendarRoute($id, $catid = 0, $language = 0)
	{
		$needles = array(
				'calendar'  => array((int) $id)
		);
	
		// Create the link
		$link = 'index.php?option=com_ukrgb&view=calendar&id=' . $id;
	
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			$link .= '&lang=' . $language;
			$needles['language'] = $language;
		}
	
		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
	
		return $link;
	}
	
	
	/**
	 * Find an item ID.
	 *
	 * @param   array  $needles  An array of language codes.
	 *
	 * @return  mixed  The ID found or null otherwise.
	 *
	 */
	protected static function _findItem($needles = null)
	{
		$app      = JFactory::getApplication();
		$menus    = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();
		
		if ($active	&& $active->component == 'com_ukrgb')
		{
			return $active->id;
		}
		//TODO - everything uses the default language for the foreseeable future.
		// If not found, return language specific home link
		$default = $menus->getDefault($language);

		return !empty($default->id) ? $default->id : null;
	}
}
