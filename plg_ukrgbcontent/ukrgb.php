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
	
	public function onContentPrepareForm($form, $data)
	{
		
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
		$this->init();
		$this->logger->log("onContentBeforeSave", JLog::DEBUG);
		if ($context == 'com_content.article' && $this->is_riverguide_category($article->catid))
		{
			$attribs = json_decode($article->attribs);	
			if ($attribs->grade == "-1"){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_("COM_UKRGB_GRADE_MISSING") , 'error');

				return false;
			}
			
		}
		return true;
	}
	

	
	public function onContentAfterSave($context, $article, $isNew)
	{
		if ($context == 'com_content.article' && $this->is_riverguide_category($article->catid))
		{
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
