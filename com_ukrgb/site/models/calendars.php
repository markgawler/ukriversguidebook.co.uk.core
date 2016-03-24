<?php
/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
require_once JPATH_COMPONENT .'/helpers/log.php';

/**
 * This models supports retrieving lists of events.
 *
 */
class UkrgbModelCalendars extends JModelList
{
	private $logger ;
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		$this->logger = new UkrgbLogger();
		
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		$app = JFactory::getApplication();
		

		$params = $app->getParams();
		$this->setState('params', $params);
		$user = JFactory::getUser();

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
		$this->setState('layout', $app->input->getString('layout'));

	}


	/**
	 * Get the master query for retrieving a list of events subject to the model state.
	 *
	 * @return  JDatabaseQuery
	 *
	 */
	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$user = JFactory::getUser();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select',
						'a.id, a.catid, a.title, a.alias, a.description, a.params ')
				);
		$query->from('#__ukrgb_calendar AS a');
		
		// Define null and now dates
		//$nullDate = $db->quote($db->getNullDate());
		//$nowDate  = $db->quote(JFactory::getDate()->toSql());
		
		// Filter by start and end dates.
		//$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
		//	->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		
		$query->where('a.state = 1' );
	
		return $query;
	}

	
	public function getItems()
	{
		//$this->logger->log('Get Items');
		
		$items = parent::getItems();

		$user = JFactory::getUser();
		$userId = $user->get('id');
		$guest = $user->get('guest');
		$groups = $user->getAuthorisedViewLevels();
		$input = JFactory::getApplication()->input;

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_ukrgb', true);

		$this->canCreate = False;
		
		// Convert the parameter fields into objects.
		foreach ($items as &$item)
		{
			$registry = new Registry;
			$item->params = $registry;
			
			//error_log($item->id);
			//$this->logger->log("Item: " . $item->id);
			
			// Technically guest could edit an event, but lets not check that to improve performance a little.
			if (!$guest)
			{
				
				$asset = 'com_ukrgb.category.' . $item->catid;
				$this->logger->log("asset      " . $asset);
				$this->logger->log("edit.state " . $user->authorise('core.edit.state', $asset));
				$this->logger->log("edit       " . $user->authorise('core.edit', $asset));
				$this->logger->log("create     " . $user->authorise('core.create', $asset));
				$this->logger->log("edit.own   " . $user->authorise('core.edit.own', $asset));
				
				// Check general edit permission first.
				if ($user->authorise('core.create', $asset))
				{
					$item->params->set('access-create', true);
					$this->canCreate = True;
				}
			}
				
		}
		return $items;
	}
	
	/**
	 * Returns True if the user can create a Calender entry.
	 * 	 * @return boolean
	 */
	public function getCanCreateEntry(){
		return $this->canCreate;
	}
	
	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return  integer  The starting number of items available in the data set.
	 *
	 * @since   12.2
	 */
	//public function getStart()
	//{
	//	return $this->getState('list.start');
	//}
	
}
