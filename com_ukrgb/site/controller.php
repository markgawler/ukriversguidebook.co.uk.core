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
	
	/**
	 * @var		string	The default view.
	 */
	protected $default_view = 'events';
	
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	 *
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$view   = $this->input->get('view', 'events');
		$layout = $this->input->get('layout', 'events');
		$id     = $this->input->getInt('id');
	
		if ($view == 'form'){
			// delete me at some point
			//throw new Exception ("Invalid view name form",500);
			return JError::raiseError(500, 'Invalid view name form');
		}
		
		// Check for edit form.
		if ($view == 'evform' && !$this->checkEditId('com_ukrgb.edit.event', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
		
		return parent::display();
	}
	
	
}
