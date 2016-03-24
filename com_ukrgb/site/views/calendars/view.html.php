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
class UkrgbViewCalendars extends JViewLegacy
{
	protected $calendars;
	
	protected $user;
		
	protected $params;
		
	protected $state;
	
	protected $autoloadLanguage = true;
	
	protected $slug;
		
	
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$dispatcher = JEventDispatcher::getInstance();
		
		$this->calendars  = $this->get('Items');
		$this->user  = $user;
		$this->canCreate = $this->get('CanCreateEntry');
		$this->state = $this->get('State');
		
		$this->cat = array();
		foreach ($this->calendars as $cal){
			$cal->slug = $cal->alias ? ($cal->id . ':' . $cal->alias) : $cal->id;
			if (!isset($this->cat[$cal->catid])){
				// make a category object with just the data we need!
				$cat = new stdClass();
				$cat->id = $cal->catid;
				$this->cat[$cal->catid] = $cat;
			}
		}
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
