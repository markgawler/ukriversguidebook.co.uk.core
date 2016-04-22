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

/**
 * HTML View class for the Ukrgb Component
 */
class UkrgbViewEvform extends JViewLegacy
{
	protected $item;
	
	protected $form;
	
	protected $return_page;
			
	protected $state;
			
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$user        = JFactory::getUser();
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		
		$this->return_page = $this->get('ReturnPage');
		$this->form  = $this->get('Form');
		
		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_ukrgb') || (count($user->getAuthorisedCategories('com_ukrgb', 'core.create')));
		}
		else
		{
			$authorised = $this->item->params->get('access-edit');
		}
		
		if ($authorised !== true)
		{
			//echo "access-edit" . $this->item->params->get('access-edit').'<br>';
			//echo "Auth: " . $authorised .'<br>';
			//echo "Id: " . $this->item->id . '<br>';
			//die();
			
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		
			return false;
		}
		
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
		
			return false;
		}
		$params = &$this->state->get('params');
		
		$this->params = $params;
		
		$this->params->merge($this->item->params);
		
		$this->user   = $user;
		
		// Display the view
		parent::display($tpl);
	}
}
