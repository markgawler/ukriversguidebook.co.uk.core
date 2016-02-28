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
class UkrgbViewEvents extends JViewLegacy
{
	protected $events;
	
	protected $user;
		
	protected $params;
	
	protected $print;
		
	protected $state;
	
	protected $autoloadLanguage = true;
	
	protected $slug;
	
	protected $pagination = null;
	
	
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$app        = JFactory::getApplication();
		$user       = JFactory::getUser();
		$dispatcher = JEventDispatcher::getInstance();
		
		$this->events  = $this->get('Items');
		$this->print = $app->input->getBool('print');
		$this->state = $this->get('State');
		$this->user  = $user;
		$this->pagination = $this->get('Pagination');
		
		foreach ($this->events as $event){
			$event->slug = $event->alias ? ($event->id . ':' . $event->alias) : $event->id;
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
