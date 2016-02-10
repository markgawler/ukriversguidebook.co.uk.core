<?php
/**
 * This is the UKRGB Event Bot plugin helper file
 *
 * PHP version 5
 *
 * @category   UKRGB
 * @package    Plugins
 * @subpackage EventBot Helper File
 * @author     Mark Gawler
 * @copyright   Copyright (C) 2016 Mark Gawler. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC' ) or die('Restricted access' );


class UkrgbEventBotHelper {

	protected $logger;
	protected $isNew;
	protected $event;
	protected $jname;
	
	function __construct($event, $isNew) {
		$this->logger = new UkrgbLogger();
		$this->event = $event;
		$this->isNew = $isNew;
		$componentPrams = JComponentHelper::getParams('com_ukrgb');
		$jPluginParamRaw = unserialize(base64_decode($componentPrams->get('JFusionPluginParam')));
		$this->jname = $jPluginParamRaw['jfusionplugin'];
	}
	
	
	public function validate()
	{
		// Validate the forum id, will throw an exception if fails to validate
		$f  =  $this->getForumIdfromEventID($this->event->calid);
		
		return true;
	}
	
	
	private function getForumIdfromEventID($id)
	{
		$db = JFactory::getDBO();
	
		// Get the forum id
		$query = $db->getQuery(true)
		->select(array('forumid'))
		->from('#__ukrgb_calendar')
		->where('state = 1')
		->where('id =' . $db->quote($id));
	
		$db->setQuery($query);
		$fid = $db->loadResult();
		
		if (is_null($fid) || $fid == 0){
			throw new Exception('Invalid calendar Id for event: ' . $id);
		}
		return $fid;
	}
	
	
	public function createThread()
	{
		$this->logger->log("Create Thread called - event id: ". $this->event->id);
		$params = $this->setParams();
		
		// Compile the event post text.
		$this->event->introtext = $this->createPostText();
		
		//make sure the forum and thread still exists
		$Forum = JFusionFactory::getForum($this->jname);
		
		$status = array('error' => array(), 'debug' => array());
		$status['threadinfo'] = new stdClass();
					
		$Forum->createThread($params, $this->event, $this->getForumIdfromEventID($this->event->calid), $status);
		
		if (!empty($status['error'])) {
			throw new Exception('com_ukrgb EventBot error: ' . $status['error'] .', ' .$this->jname. ' ' . JText::_('FORUM') . ' ' . JText::_('UPDATE'));
		} else {
			$threadinfo = $status['threadinfo'];
			$this->logger->log("	Created: " . $threadinfo->postid);
			$this->setThreadInfo($threadinfo);
		}
		return true;
	}
	
	public function updateThread()
	{
		$this->logger->log("Update Thread called - event id: ". $this->event->id);
		$params = $this->setParams();
		
		$threadinfo = new stdClass();
		$threadinfo->threadid = $this->event->threadid;
		$threadinfo->postid = $this->event->postid;
		$threadinfo->forumid = $this->event->forumid;
		
		// Compile the event post text.
		$this->event->introtext = $this->createPostText();
		
		//make sure the forum and thread still exists
		$Forum = JFusionFactory::getForum($this->jname);
		
		$status = array('error' => array(), 'debug' => array());
		$status['threadinfo'] = new stdClass();
			
		$Forum->updateThread($params, $threadinfo, $this->event, $status);
		
		if (!empty($status['error'])) {
			
			//throw new Exception('com_ukrgb EventBot error: ' . $status['error'] .', ' .$this->jname. ' ' . JText::_('FORUM') . ' ' . JText::_('UPDATE'));
			var_dump($status);
			die();
		} else {
			$threadinfo = $status['threadinfo'];
			$this->logger->log("	updated: " . $threadinfo->postid);
			$this->setThreadInfo($threadinfo);
		}
		return true;
	}
	
	private function setParams(){
		$dbparams = new stdClass();
		$dbparams->jname = $this->jname;
		$dbparams->debug = true;
		$dbparams->use_content_created_date = true;
		return new JRegistry($dbparams);
	}
	
	private function createPostText(){
		return $this->event->summary;
	}
	
	private function setThreadInfo($info)
	{
		$info->id = $this->event->id;
		
		$result = JFactory::getDBO()->updateObject('#__ukrgb_events', $info, 'id');		
		if ($result){
			$this->logger->log("Event: updated with thread info " . $result);
		}
		return true;
		
	}
}
