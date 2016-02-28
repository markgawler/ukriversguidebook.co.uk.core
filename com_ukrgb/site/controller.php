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
		die('-- Event --');

		$input = JFactory::getApplication()->input;
		$input->set('view','event');
		parent::display();
	}
	
	function events()
	{
		die('-- Events --');
		$input = JFactory::getApplication()->input;
		$input->set('view','events');
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
	

		// Check for edit form.
		if ($view == 'event' && $layout == 'edit' && !$this->checkEditId('com_ukrgb.edit.event', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_ukrgb&view=events', false));
	
			return false;
		}
	
		return parent::display();
	}
	
	
}
