<?php
/**
* @package		Joomla
* @subpackage	Content
* @copyright	Copyright (C) 2015 Mark Gawler. All rights reserved.
* @link		http://www.ukriversguidebook.co.uk
* @license		License GNU General Public License version 2 or later
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

//jimport( 'joomla.plugin.plugin' );

class plgContentUkrgb extends JPlugin {
	
	protected $autoloadLanguage = true;
	
	public function onContentPrepareForm($form, $data)
	{
		
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
	
		JForm::addFormPath(dirname(__FILE__) . '/fields');
		$form->loadFile('riverguide', false);
		
		if (isset($data->catid) && $this->is_riverguide_category($data->catid))
		{
			// Add the extra fields to the form.
			// need a seperate directory for the installer not to consider the XML a package when "discovering"
			
			

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
		if ($context == 'com_content.article' && $this->is_riverguide_category($data->catid))
		{
			$db = JFactory::getDBO();
		
			$query = $db->getQuery(true)
			->select(array('summary','dificulty'))
			->from('#__ukrgb_riverguide')
			->where('id  = ' . $data->id);
			
			$db->setQuery($query);
			$rg = $db->loadObject();
			if (!empty($rg))
			{
				//$data->$rg;
				$data->riverguide = array(
						'summary' => $rg->summary,
						'grade' => $rg->dificulty);
			}
		}
	}

	
	/*
	 * 
	public function onContentBeforeSave($context, $article, $isNew)
	{
		echo "onContentBeforeSave";
		
		if ($context == 'com_content.article')
		//&& $this->is_riverguide_category($data->catid))
		{
			var_dump($article);
			//die();
		}
		return ;
	}
	*/

	
	public function onContentAfterSave($context, $article, $isNew)
	{
		
		if ($context == 'com_content.article' && $this->is_riverguide_category($article->catid))
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_ukrgb/tables');
			$table = JTable::getInstance('Riverguide', 'UkrgbTable', array());	
			
			$attribs = json_decode($article->attribs);
			
			$data = array('id' => $article->id,
					'catid' => $article->catid,
					'dificulty' => $attribs->grade,
					'summary' => $attribs->summary);
			
			$sucsess = $table->save($data);
			if (!$sucsess)
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage('Failed to save: ' . $summary , 'warning');
			}	
		}
		return true;
	}
	
	private function is_riverguide_category($catid)
	{
		jimport('joomla.application.component.helper');
		$config = JComponentHelper::getParams('com_ukrgb');
		return (in_array($catid, $config->get('riverguidecats')));
	}
	
}
