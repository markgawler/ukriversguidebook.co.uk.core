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
		//make sure the forum and thread still exists
		$this->forum = JFusionFactory::getForum($this->jname);
		
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
	
	public function isValidThread()
	{	
		return empty($this->forum->getThread($this->event->threadid));
	}
	
	public function createThread()
	{
		$this->logger->log("Create Thread called - event id: ". $this->event->id);
		$params = $this->setParams();
		$forumId = $this->getForumIdfromEventID($this->event->calid);
		
		// Compile the event post text.
		$this->event->introtext = $this->createPostText($this->event->calid);
		
		$status = array('error' => array(), 'debug' => array());
		$status['threadinfo'] = new stdClass();
					
		$this->forum->createThread($params, $this->event, $forumId, $status);
		
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
		$this->event->introtext = $this->createPostText($this->event->calid);
		
		$status = array('error' => array(), 'debug' => array());
		$status['threadinfo'] = new stdClass();
			
		$this->forum->updateThread($params, $threadinfo, $this->event, $status);
		if (!empty($status['error'])) {
			throw new Exception('com_ukrgb EventBot error: ' . $status['error'] .', ' .$this->jname. ' ' . JText::_('FORUM') . ' ' . JText::_('UPDATE'));
		}
		return true;
	}
	
	private function setParams(){
		$dbparams = new stdClass();
		$dbparams->jname = $this->jname;
		$dbparams->debug = true;
		$dbparams->use_content_created_date = true;
		$dbparams->first_post_link = false; // we need to create our own link JF always creates com_content links
		return new JRegistry($dbparams);
	}
	
	
	private function createPostText($cal){
		$db = JFactory::getDBO();
		
		// Get the forum id
		$query = $db->getQuery(true)
		->select(array('post_template'))
		->from('#__ukrgb_calendar')
		->where('state = 1')
		->where('id =' . $db->quote($cal));
		
		$db->setQuery($query);
		$template = $db->loadResult();
		
		$matches = array();
		$count = preg_match_all('#\{\w*\}#', $template, $matches);
		foreach ($matches[0] as $match){
			$propName = trim($match,'{}');
			$this->event->link = trim(JURI::root(false, JRoute::_(UkrgbHelperRoute::getEventRoute($this->event->id))),'/');
			if (property_exists($this->event, $propName)){
				$template = str_replace($match, $this->event->$propName, $template);
			} else {
				$this->logger->log("template property: " . $match . "  Invalid");
			}
		}
		return $template;
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
