<?php
/**
 * @copyright	Copyright (C) 2011 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldCalendarList extends JFormFieldList

{ 
	public $type = 'calendarlist';
	
	/**
	 * 	Generates a Calendar select list.
	 * {@inheritDoc}
	 * @see JFormFieldList::getOptions()
	 */
	protected function getOptions()
	{		
		$db = JFactory::getDBO();
		//$id = JFactory::getApplication()->input->getInt('id', 0);

		$query = $db->getQuery(true)
		->select(array('id','title'))
		->from('#__ukrgb_calendar')
		->where('state = 1')
		->order('ordering ASC');

		$db->setQuery($query);
		$cals = $db->loadRowList();

		$options = array();
		$options[] = array("value" => 0, "text" => "");
					foreach ($cals as $r){
			$options[] = array("value" => $r[0], "text" => $r[1]);
		}
		return $options;
	}
}
