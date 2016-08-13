<?php

/**
 * @package		UKRGB
 * @subpackage	Component
 * @copyright	Copyright (C) 2015 -2016. All rights reserved.
 * @author		Mark Gawler
 * @link		http://ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

// Base this model on the backend version.
require_once JPATH_ADMINISTRATOR . '/components/com_ukrgb/models/event.php';
require_once JPATH_COMPONENT .'/helpers/log.php';

/**
 * Ukrgb Component event Model
 *
 */
class UkrgbModelEvform extends UkrgbModelEvent
{
	private $logger ;
	public function __construct($config = array())
	{
		$this->logger = new UkrgbLogger();
		$this->logger->log("UkrgbModelEvform __construct");
		
		return parent::__construct($config);
	}

	public function getTable($type = 'Event', $prefix = 'UkrgbTable', $config = array())
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_ukrgb/tables');
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 */
	protected function populateState()
	{		
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('a_id');
		$this->setState('event.id', $pk);
		$this->setState('event.catid', $app->input->getInt('catid'));

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		$this->setState('layout', $app->input->getString('layout'));
	}

	/**
	 * Method to get event data.
	 *
	 * @param   integer  $itemId  The id of the event.
	 *
	 * @return  mixed  event data object on success, false on failure.
	 */
	public function getItem($itemId = null)
	{
		//die('UkrgbModelEvform getItem');
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('event.id');
		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());
			return false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');

		$catId = $value->catid;
		
		// Convert attrib field to Registry.
		$value->params = new Registry;

		$this->logger->log("UkrgbModelEvform CatId: " . $catId);
		
		// Compute selected asset permissions.
		$user   = JFactory::getUser();
		$userId = $user->get('id');
		$asset  = 'com_ukrgb.category.' . $catId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			$value->params->set('access-edit', true);
		}

		// Now check if edit.own is available.
		elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
		{
			// Check for a valid user and that they are the owner.
			if ($userId == $value->created_by)
			{
				$value->params->set('access-edit', true);
			}
		}

		// Check edit state permission.
		if ($itemId)
		{
			// Existing item
			$value->params->set('access-change', $user->authorise('core.edit.state', $asset));
		}
		else
		{
			// New event.
			if ($catId)
			{
				$value->params->set('access-change', $user->authorise('core.edit.state', 'com_ukrgb.category.' . $catId));
				$value->catid = $catId;
			}
			else
			{
				$value->params->set('access-change', $user->authorise('core.edit.state', 'com_ukrgb'));
			}
		}
		return $value;
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string	The return URL.
	 *
	 * @since   1.6
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.2
	 */
	public function save($data)
	{
		$data['state'] = 1;  // force published
		return parent::save($data);
	}

}
