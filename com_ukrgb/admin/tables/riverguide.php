<?php
/**
* @package     Joomla.Administrator
* @subpackage  com_ukrgb
*
* @copyright   Copyright (C) 2015 - 2015 Mark Gawler, Inc. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 */
class UkrgbTableRiverguide extends JTable
{
	/**
	 * @param   JDatabaseDriver  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__ukrgb_riverguide', 'id', $db);
	}
	
	/**
	 * Oveeride of Save which creates the row if it dose not exist.
	 * @see JTable::save()
	 */
	public function save($src, $orderingFilter = '', $ignore = '')
	{
		$id = $src['id'];
		
		// test if the row exists
		$db = $this->getDbo();
		$query = $db->getQuery(true)
		->select(count(1))
		->from('#__ukrgb_riverguide')
		->where('id  = ' . $id); 
		$db->setQuery($query);
		
		if (empty($db->loadObjectList())){
			// Failed to load the guide data so create a blank entry.
			$query = $db->getQuery(true);
			$query->insert('#__ukrgb_riverguide')
			->columns($db->quoteName(array('id',)))
			->values(implode(',', array((int) $id)));
			$db->setQuery($query);
			$result = $db->query();
		}
		return parent::save($src, $orderingFilter, $ignore);
	}
}
