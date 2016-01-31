<?php
/**
 * @version		$Id: event.php 284 2011-11-11 16:17:14Z dextercowley $
 * @copyright	Copyright (C) 2011 Mark Dexter and Louis Landry. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Ukrgbevents model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_ukrgb
*/
class UkrgbModelEvent extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_UKRGB';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return ;
			}
			$user = JFactory::getUser();

			if ($record->catid) {
				return $user->authorise('core.delete', 'com_ukrgb.category.'.(int) $record->catid);
			}
			else {
				return parent::canDelete($record);
			}
		}
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) 
		{
			return $user->authorise('core.edit.state', 'com_ukrgb.category.'.(int) $record->catid);
		}
		else 
		{
			return parent::canEditState($record);
		}
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'event', $prefix = 'UkrgbTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_ukrgb.event', 'event', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('event.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_ukrgb.edit.event.data', array());
		
		if (empty($data)) {
				
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('event.id') == 0) {
				
				$app = JFactory::getApplication();
				$data->set('catid', $app->input->get('catid', $app->getUserState('com_ukrgb.eventmanager.filter.category_id'), 'int'));
			}
		}

		$this->preprocessData('com_ukrgb.event', $data);

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias = JApplication::stringURLSafe($table->alias);
		
		
		//$sdate= new JData($table->start_date);
		//var_dump($table->start_date);die();
		
		
		
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}
		
		if (empty($table->id))
		{
			// Set the values
		
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ukrgb_events');
				$max = $db->loadResult();
		
				$table->ordering = $max + 1;
			}
			else
			{
				// Set the values
				$table->modified    = $date->toSql();
				$table->modified_by = $user->get('id');
			}
		}
		
		// Increment the event version number.
		$table->version++;
		
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 */
	public function save($data)
	{
		$app = JFactory::getApplication();
	
		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy')
		{
			list($name, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['title']	= $name;
			$data['alias']	= $alias;
			$data['state']	= 0;
		}
		return parent::save($data);
	}
	
	/**
	 * Method to change the title & alias.
	 *
	 * @param   integer  $category_id  The id of the parent.
	 * @param   string   $alias        The alias.
	 * @param   string   $name         The title.
	 *
	 * @return  array  Contains the modified title and alias.
	 */
	protected function generateNewTitle($category_id, $alias, $name)
	{
		// Alter the title & alias
		$table = $this->getTable();
	
		while ($table->load(array('alias' => $alias, 'catid' => $category_id)))
		{
			if ($name == $table->title)
			{
				$name = JString::increment($name);
			}
	
			$alias = JString::increment($alias, 'dash');
		}
	
		return array($name, $alias);
	}
	
}