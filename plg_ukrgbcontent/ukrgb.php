<?php
/**
* @package		Joomla
* @subpackage	Content
* @copyright	Copyright (C) 2015 Mark Gawler. All rights reserved.
* @link		http://www.ukriversguidebook.co.uk
* @license		License GNU General Public License version 2 or later
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.log.log');

require_once JPATH_ROOT .'/components/com_ukrgb/helpers/log.php';

class plgContentUkrgb extends JPlugin {
	private $logger;
	
	protected $autoloadLanguage = true;
	
	
	/**
	 * Constructor
	 *
	 * @param object &$subject The object to observe
	 * @param array|object  $params   An array or object that holds the plugin configuration
	 *
	 * @since 1.5
	 */
//	public function __construct(&$subject, $params)
//	{
//		parent::__construct($subject, $params);
//	}
	
	public function onContentPrepareForm($form, $data)
	{
		// River guides - additional fields for article
		//$this->init();
		//$this->logger->log("onContentPrepareForm ");
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		} 
		$name = $form->getName();
		$path = explode('.',$name);
		if ($path[0] != 'com_content')
		{
			return true;
		} 
		
		
		// Add the extra fields to the form.
		JForm::addFormPath(dirname(__FILE__) . '/fields');
		$form->loadFile('riverguide', false);
		
		if (isset($data->catid) && $this->is_riverguide_category($data->catid))
		{
			// load the data in to the form
			if (!empty($data->riverguide))
			{
				$form->setValue('summary','attribs',$data->riverguide['summary']);
				$form->setValue('grade','attribs',$data->riverguide['grade']);
			}
		}
		return true;
	}

	
	public function onContentPrepareData($context, $data)
	{
		// River Guides - load additional fields
		//$this->init();
		//$this->logger->log("onContentPrepareData " . $context);
		if ($context == 'com_content.article' && isset($data->catid) && $this->is_riverguide_category($data->catid))
		{
			if (isset($data->id))
			{
				$db = JFactory::getDBO();
			
				$query = $db->getQuery(true)
				->select(array('summary','grade'))
				->from('#__ukrgb_riverguide')
				->where('id  = ' . $data->id);
				
				$db->setQuery($query);
				$rg = $db->loadObject();
			}
			if (!empty($rg))
			{
				$data->riverguide = array(
						'summary' => $rg->summary,
						'grade' => $rg->grade);
			}
		}
	}

	
	
	 
	public function onContentBeforeSave($context, $article, $isNew) 
	{
		//$this->init();
		//$this->logger->log("onContentBeforeSave " . $context);
		if (($context == 'com_content.article' || $context == 'com_content.form') && $this->is_riverguide_category($article->catid))
		{

			$attribs = json_decode($article->attribs);
			if ($attribs->grade == "-1"){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_("COM_UKRGB_GRADE_MISSING") , 'error');

				return false;
			}
		}elseif ($context = 'com_ukrgb.event'){
			// Event Bot
			include_once JPATH_SITE . '/plugins/content/ukrgb/helper/helper.php';
			$this->helper = new UkrgbEventBotHelper($article, $isNew);
			return $this->helper->validate();
		}
		return true;
	}
	

	
	public function onContentAfterSave($context, $article, $isNew)
	{
		$this->init();
		$this->logger->log("onContentAfterSave " . $context);
		
		if (($context == 'com_content.article' || $context == 'com_content.form') && $this->is_riverguide_category($article->catid))
		{
			//River Guides
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_ukrgb/tables');
			$table = JTable::getInstance('Riverguide', 'UkrgbTable', array());	
			
			$attribs = json_decode($article->attribs);
			
			$data = array('id' => $article->id,
					'catid' => $article->catid,
					'grade' => $attribs->grade,
					'summary' => $attribs->summary);
			
			$sucsess = $table->save($data);
			if (!$sucsess)
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_("COM_UKRGB_GUIDE_SAVE_FAIL"). ':' .$article->title  , 'warning');
			}	
		} elseif ($context = 'com_ukrgb.event'){
			// Event Bot
			include_once JPATH_SITE . '/plugins/content/ukrgb/helper/helper.php';
			$this->helper = new UkrgbEventBotHelper($article, $isNew);
			
			// If new event or thread info invalid create a new thread
			if ($isNew || $article->forumid == 0 || $article->threadid == 0 || $article->postid == 0 ) {
				$this->helper->createThread();
			} else {
				$this->helper->updateThread();
			}
			
		}
		return true;
	}
	
	
	public function onContentAfterDelete($context, $article)
	{
		if ($context == 'com_content.article' && $this->is_riverguide_category($article->catid))
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_ukrgb/tables');
			$table = JTable::getInstance('Riverguide', 'UkrgbTable', array());
			$table->delete($article->id);
		} 
	}
	
	private function init()
	{
		$this->logger = new UkrgbLogger();
	}
	
	private function is_riverguide_category($catid)
	{
		jimport('joomla.application.component.helper');
		$config = JComponentHelper::getParams('com_ukrgb');
		return (in_array($catid, $config->get('riverguidecats')));
	}
	
}
