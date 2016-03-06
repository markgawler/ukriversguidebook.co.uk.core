<?php

/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die();

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Ukrgb Component
 */
class UkrgbViewEvent extends JViewLegacy
{
	protected $event;
	
	protected $user;
		
	protected $params;
	
	protected $print;
		
	protected $state;
	
	protected $autoloadLanguage = true;
	
		
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$dispatcher = JEventDispatcher::getInstance();
		
		$this->event  = $this->get('Item');
		$this->print = $app->input->getBool('print');
		$this->state = $this->get('State');
		$this->user  = $user;
		
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
		
			return false;
		}
		$this->params = $this->state->get('params');

				
		// Display the view
		parent::display($tpl);
	}
}
