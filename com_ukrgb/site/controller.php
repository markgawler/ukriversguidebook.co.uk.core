<?php

/**
 * @package		UKRGB
 * @subpackage	Component
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @author		Mark Gawler
 * @link		http://ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
jimport('joomla.client.http');
/**
 * UkrgbController Component Controller
 */
class UkrgbController extends JControllerLegacy
{
	function donation()
	{	
		$input = JFactory::getApplication()->input;
		$input->set('view','donation');
		parent::display();
	}
	
	function event()
	{
		//echo "event";
		//die();
		$input = JFactory::getApplication()->input;
		$input->set('view','event');
		parent::display();
	}
}
