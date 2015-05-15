<?php
defined('_JEXEC') or die;
/*
 * Tagtool model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 * 
 * @copyright   Copyright (C) 2015 - 2015 Mark Gawler. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 */
class UkrgbModelTagtool extends JModelAdmin
{

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_ukrgb.tagtool';

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_UKRGB';

	/**
	 * Abstract method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_ukrgb.tagtool', 'tagtool', array('control' => 'jform'));

		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	
	/**
	 * method to grade the content specified by the urls in the file.
	 *
	 * @param   int  $grade      the tags to apply to the content.
	 * @param   array  $file
	 *
	 * @return  boolean 
	 *
	 */
	public function gradeContent($grade, $userfile)
	{
		$app	= JFactory::getApplication();
		
		$config = JComponentHelper::getParams('com_ukrgb');
				
		// Check if there was a  problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1)
		{
			$app->enqueueMessage(JText::_('COM_UKRGB_TAGTOOL_MSG_UPLOADERROR'),'warning');
			return false;
		}
		$tmp_name = $userfile['tmp_name'];
		
		
		$file = fopen($tmp_name,"r");
		while(! feof($file))
		{
			$field_array = (fgetcsv($file));
			$url = trim(preg_replace('/\s+/', ' ', $field_array[0]));
			$summary = trim($field_array[1]);
			$item_data = $this->_get_item_data_from_url($url);
			$item_data['summary'] = $summary;
			$item_data['grade'] = $grade;

			if ($item_data['id'] != False){
				//$this->_apply_tag($item_data['id'], $tags);
				$this->_apply_river_data($item_data);
			}
			else {
				$app->enqueueMessage('No article for: ' . $url , 'warning');
			}
		}
		fclose($file);
		
		return true;
	}
	
	protected function _get_item_data_from_url($url)
	{
		// Get the Item id from the sefurls extension table.
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true)
		->select('newurl')
		->from('#__sh404sef_urls')
		->where('oldurl = ' . $db->quote($url));
				
		$db->setQuery($query);
		$origurl = $db->loadResult();
		if (!empty($origurl))
		{
			$uri = new JUri($origurl);
			$id = $uri->getVar('id');
			$catid = $uri->getVar('catid');
			return array('id' => $id, 'catid' => $catid);
		}
		return false;
	}
	
	protected function _apply_river_data($data)
	{
		
		$table = JTable::getInstance('Riverguide', 'UkrgbTable', array());
		if (!$table){
			echo "Fail!<br>";
			die();
		}
		$id = $data['id'];
		if (!$table->load($id)){
			// Failed to load the guide data so create a blank entry. 
			// TODO override save to do this automatically  
			$db = $table->getDbo();
			$query = $db->getQuery(true);
			$query->insert($table->getTableName())
			->columns($db->quoteName(array('id',)))
			->values(implode(',', array((int) $id)));
			$db->setQuery($query);
			$result = $db->query();	
		}
		
		$sucsess = $table->save($data);
		if (!$sucsess)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Failed to save: ' . $summary , 'warning');
		}
			
	}	

}
