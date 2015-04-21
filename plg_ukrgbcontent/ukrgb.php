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
			echo "plgContentUkrgb - JERROR_NOT_A_FORM";
				
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		
		if ($form->getName() == 'com_content.article' && !empty($data->catid))
		{
			jimport('joomla.application.component.helper');
			$config = JComponentHelper::getParams('com_ukrgb');
			if ($this->is_riverguide_category($data->catid))
			{
				// Add the extra fields to the form.
				JForm::addFormPath(__DIR__ . '/fields');
				$form->loadFile('riverguide', false);

				// load the data in to the form
				$form->setValue('summary','attribs',$data->summary);
				$form->setValue('grade','attribs',$data->dificulty);
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
				$data->summary = $rg->summary;
				$data->dificulty = $rg->dificulty;		
			}
		}
	}
	
	
	public function onContentBeforeSave($context, $article, $isNew)
	{
		echo "onContentAfterSave";
		
		if ($context == 'com_content.article')
		//&& $this->is_riverguide_category($data->catid))
		{
			var_dump($article);
			die();
		}
		return ;
	}
	
	public function onContentAfterSave($context, $article, $isNew)
	{
		echo "onContentAfterSave";
		
		if ($context == 'com_content.article') 
				//&& $this->is_riverguide_category($data->catid))
		{
			var_dump($article);
			//die();	
		}
		return ;
	}
	
	private function is_riverguide_category($catid)
	{
		jimport('joomla.application.component.helper');
		$config = JComponentHelper::getParams('com_ukrgb');
		return (in_array($catid, $config->get('riverguidecats')));
	}
	
}
