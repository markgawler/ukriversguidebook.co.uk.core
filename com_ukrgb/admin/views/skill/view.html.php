<?php
/**
 * @copyright	Copyright (C) 2014 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * View to edit a skill.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_ukrgb
*/
class UkrgbViewSkill extends JViewLegacy

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
		
		JToolbarHelper::title(JText::_('COM_UKRGB_MANAGER_SKILL'), 'newfeeds.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_ukrgb', 'core.create')))))
		{
			JToolbarHelper::apply('skill.apply');
			JToolbarHelper::save('skill.save');
		}
		if (!$checkedOut && (count($user->getAuthorisedCategories('com_ukrgb', 'core.create'))))
		{
			JToolbarHelper::save2new('skill.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_ukrgb', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('skill.save2copy');
		}
		if (empty($this->item->id)) {
			JToolbarHelper::cancel('skill.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_ukrgb.skill', $this->item->id);
			}

			JToolbarHelper::cancel('skill.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('COM_UKRGB_SKILL_HELP_LINK');
	}
}
