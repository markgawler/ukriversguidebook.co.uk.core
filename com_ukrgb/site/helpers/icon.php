<?php

/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * UKRGB Component HTML Helper
 *
 */
abstract class JHtmlIcon
{
	
	/**
	 * Display an edit icon for the event.
	 *
	 * This icon will not display in a popup window, nor if the event is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $event  The event information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string	The HTML for the event edit icon.
	 *
	 * @since   1.6
	 */
	public static function edit($event, $params, $attribs = array(), $legacy = false)
	{
		$user = JFactory::getUser();
		$uri  = JUri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return;
		}

		// Ignore if the state is negative (trashed).
		if ($event->state < 0)
		{
			return;
		}

		JHtml::_('bootstrap.tooltip');

		// Show checked_out icon if the event is checked out by a different user
		if (property_exists($event, 'checked_out')
			&& property_exists($event, 'checked_out_time')
			&& $event->checked_out > 0
			&& $event->checked_out != $user->get('id'))
		{
			$checkoutUser = JFactory::getUser($event->checked_out);
			$date         = JHtml::_('date', $event->checked_out_time);
			$tooltip      = JText::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . JText::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;

			if ($legacy)
			{
				$button = JHtml::_('image', 'system/checked_out.png', null, null, true);
				$text   = '<span class="hasTooltip" title="' . JHtml::tooltipText($tooltip . '', 0) . '">'
					. $button . '</span> ' . JText::_('JLIB_HTML_CHECKED_OUT');
			}
			else
			{
				$text = '<span class="hasTooltip icon-lock" title="' . JHtml::tooltipText($tooltip . '', 0) . '"></span> ' . JText::_('JLIB_HTML_CHECKED_OUT');
			}

			$output = JHtml::_('link', '#', $text, $attribs);

			return $output;
		}

		$url = 'index.php?option=com_ukrgb&task=event.edit&a_id=' . $event->id . '&return=' . base64_encode($uri);

		if ($event->state == 0)
		{
			$overlib = JText::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = JText::_('JPUBLISHED');
		}

		$date   = JHtml::_('date', $event->created);
		$author = $event->created_by_alias ? $event->created_by_alias : $event->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= JText::sprintf('COM_CONTENT_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		if ($legacy)
		{
			$icon = $event->state ? 'edit.png' : 'edit_unpublished.png';

			if (strtotime($event->publish_up) > strtotime(JFactory::getDate())
				|| ((strtotime($event->publish_down) < strtotime(JFactory::getDate())) && $event->publish_down != JFactory::getDbo()->getNullDate()))
			{
				$icon = 'edit_unpublished.png';
			}

			$text = JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);
		}
		else
		{
			$icon = $event->state ? 'edit' : 'eye-close';

			if (strtotime($event->publish_up) > strtotime(JFactory::getDate())
				|| ((strtotime($event->publish_down) < strtotime(JFactory::getDate())) && $event->publish_down != JFactory::getDbo()->getNullDate()))
			{
				$icon = 'eye-close';
			}

			$text = '<span class="hasTooltip icon-' . $icon . ' tip" title="' . JHtml::tooltipText(JText::_('COM_CONTENT_EDIT_ITEM'), $overlib, 0, 0)
				. '"></span>'
				. JText::_('JGLOBAL_EDIT');
		}

		$output = JHtml::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}
	
	/**
	 * Method to generate a link to print an article
	 *
	 * @param   object    $article  Not used, @deprecated for 4.0
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Not used, @deprecated for 4.0
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string  The HTML markup for the popup link
	 */
	public static function print_screen($article, $params, $attribs = array(), $legacy = false)
	{
		// Checks template image directory for image, if none found default are loaded
		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
			}
			else
			{
				$text = '<span class="icon-print"></span>' . JText::_('JGLOBAL_PRINT');
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_PRINT');
		}
	
		return '<a href="#" onclick="window.print();return false;">' . $text . '</a>';
	}
}
