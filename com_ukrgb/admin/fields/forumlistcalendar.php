<?php
/**
 * @copyright	Copyright (C) 2011 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
/**
 * Require the Jfusion plugin factory
 */
require_once JPATH_ADMINISTRATOR . '/components/com_jfusion/import.php';

/**
 * FormField for selection of Forum linked to Calendar
 */

JFormHelper::loadFieldClass('list');

class JFormFieldForumlistcalendar extends JFormFieldList
{
	public $type = 'forumlistcalendar';
	/**
	 * 	Generates a Forum list selection.
	 * {@inheritDoc}
	 * @see JFormFieldList::getOptions()
	 */
	protected function getOptions()
	{
		try {
			$output = array();

			$db = JFactory::getDBO();
			$id = JFactory::getApplication()->input->getInt('id', 0);
	
			// Get the forum id
			$query = $db->getQuery(true)
			->select(array('forumid'))
			->from('#__ukrgb_calendar')
			->where('state = 1')
			->where('id =' . $db->quote($id))
			->order('ordering ASC');
	
			$db->setQuery($query);
			$selectedValue = $db->loadResult();
			
			// Get the Plugin name
			$parametersComponentInstance = JComponentHelper::getParams('com_ukrgb');
			$jPluginParamRaw = unserialize(base64_decode($parametersComponentInstance->get('JFusionPluginParam')));
			$jname = $jPluginParamRaw['jfusionplugin'];
			
			if (!empty($jname)) {
				$JFusionPlugin = JFusionFactory::getForum($jname);
				if ($JFusionPlugin->isConfigured()) {
					if (method_exists($JFusionPlugin, 'getForumList')) {
						$forumlist = $JFusionPlugin->getForumList();
						if (!empty($forumlist)) {
							// build the output list
							$output[] = array("value" => 0, "text" => "");
							foreach ($forumlist as $r){
								$output[] = array("value" => $r->id, "text" => $r->name);
							}			
						} else {
							throw new RuntimeException($jname . ': ' .JText::_('NO_LIST'));
						}
					} else {
						throw new RuntimeException($jname . ': ' .JText::_('NO_LIST'));
					}
				} else {
					throw new RuntimeException($jname . ': ' .JText::_('NO_VALID_PLUGINS'));
				}
			} else {
				throw new RuntimeException(JText::_('NO_PLUGIN_SELECT'));
			}
		} catch(Exception $e) {
			$output = '<span style="float:left; margin: 5px 0; font-weight: bold;">' . $e->getMessage() . '</span>';
		}
		return $output;
	}
	
}
