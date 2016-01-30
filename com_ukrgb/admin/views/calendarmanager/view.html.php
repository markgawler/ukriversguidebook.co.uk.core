<?php
/**
 * @copyright	Copyright (C) 2014 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * View class for a list of events.
 *
*/
class UkrgbViewcalendarmanager extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->authors = $this->get('Authors');
		$this->pagination = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		
		UkrgbHelper::addSubmenu('calendarmanager');	
                
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= JHelperContent::getActions('com_ukrgb');
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_UKRGB_MANAGER_CALENDARMANAGER'), 'stack calendarmanager');
		
		if (count($user->getAuthorisedCategories('com_ukrgb', 'core.create')) > 0)
		{
			JToolbarHelper::addNew('calendar.add');
		}
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('calendar.edit');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::publish('calendarmanager.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('calendarmanager.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('calendarmanager.archive');
			JToolbarHelper::checkin('calendarmanager.checkin');
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'calendarmanager.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('calendarmanager.trash');
		}
		// Add a batch button
		if ($user->authorise('core.create', 'com_ukrgb') && $user->authorise('core.edit', 'com_ukrgb') && $user->authorise('core.edit.state', 'com_ukrgb'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_ukrgb');
		}

		JToolbarHelper::help('JHELP_COMPONENTS_CALENDARMANAGER_LINKS');

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
