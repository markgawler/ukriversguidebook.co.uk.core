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
//jimport('joomla.application.component.view');

/**
 * HTML View class for the Ukrgb Component
 */
class UkrgbViewForm extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $return_page;
	
	protected $user;
		
	protected $params;
			
	protected $state;
	
	protected $autoloadLanguage = true;
	
		
	// Overwriting JView display method
	function display($tpl = null) 
	{
		//$app        = JFactory::getApplication();
		$user        = JFactory::getUser();
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		$this->form  = $this->get('Form');
		
		$this->return_page = $this->get('ReturnPage');
		
		//$dispatcher = JEventDispatcher::getInstance();
		
		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_ukrgb') || (count($user->getAuthorisedCategories('com_ukrfg', 'core.create')));
		}
		else
		{
			$authorised = $this->item->params->get('access-edit');
		}
		
		if ($authorised !== true)
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		
			return false;
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
