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
	 * method to tag the content specified by the urls in the file.
	 *
	 * @param   array  $tags      the tags to apply to the content.
	 * @param   array  $file
	 *
	 * @return  boolean 
	 *
	 */
	public function tagContent($tags, $userfile)
	{
		// Check if there was a  problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1)
		{
			JError::raiseWarning('', JText::_(''));
		
			return false;
		}
		
		
		echo "model";
		return true;
	}

}
