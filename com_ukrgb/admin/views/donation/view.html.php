<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * UJRGB View
*/
class UkrgbViewDonation extends JViewLegacy
{
	/**
	 * Ukrgb view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		// Get data from the model
		$items = $this->get('Items');
		#$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		#$this->pagination = $pagination;

		// Set the toolbar
		$this->addToolBar();
		
		// Display the template
		parent::display($tpl);
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		$user		= JFactory::getUser();
		
		//JToolBarHelper::title(JText::_('COM_UKRGB_DONATIONS'));
		JToolBarHelper::title('UK Rivers Guidebook');
		if ($user->authorise('core.admin', 'com_ukrgb'))
		{
			JToolbarHelper::preferences('com_ukrgb');
				
		}
		JToolbarHelper::divider();
		
	}
}