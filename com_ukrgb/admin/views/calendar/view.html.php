<?php
/**
 * @copyright	Copyright (C) 2014 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * View to edit a calendar.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_ukrgb
*/
class UkrgbViewCalendar extends JViewLegacy

{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user = JFactory::getUser();
		$isNew = ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo	= JHelperContent::getActions('com_ukrgb');
		
		JToolbarHelper::title(JText::_('COM_UKRGB_MANAGER_CALENDAR'), 'newfeeds.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_ukrgb', 'core.create')))))
		{
			JToolbarHelper::apply('calendar.apply');
			JToolbarHelper::save('calendar.save');
		}
		if (!$checkedOut && (count($user->getAuthorisedCategories('com_ukrgb', 'core.create'))))
		{
			JToolbarHelper::save2new('calendar.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_ukrgb', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('calendar.save2copy');
		}
		if (empty($this->item->id)) {
			JToolbarHelper::cancel('calendar.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_ukrgb.calendar', $this->item->id);
			}

			JToolbarHelper::cancel('calendar.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('COM_UKRGB_CALENDAR_HELP_LINK');
	}
}