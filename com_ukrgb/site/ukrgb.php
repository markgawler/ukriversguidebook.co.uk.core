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

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Ukrgb
$controller = JControllerLegacy::getInstance('Ukrgb');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller   	
$controller->redirect();
