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
	                
	public function onContentPrepareForm($form, $data)
	{
		echo "plgContentUkrgb";
		die();
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
	
		// Add the extra fields to the form.
		JForm::addFormPath(__DIR__ . '/fields');
		$form->loadFile('riverguide', false);
		return true;
	}
	public function onContentAfterSave($context, &$article, $isNew)
	{
		echo "onContentAfterSave";
		
		//die();
	}
	
}
