<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) 2015 - 2015 Mark Gawler. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 */
class UkrgbControllerTagtool extends JControllerLegacy
{
	/**
	 * Grade riverguide .
	 *
	 * @return  void
	 */
	public function upload()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$file = $this->input->files->get('url_list', null, 'array');
		
		$mainform = new JRegistry($this->input->get('jform',array(),'array'));
		$gradeform = new JRegistry($mainform->get('main', array()));

		$grade = $gradeform->get('applyGrade', array());
		
		$model = $this->getModel('tagtool');
		if ($model->gradeContent($grade, $file))
		{
			$app	= JFactory::getApplication();
			$app->enqueueMessage( JText::_('COM_UKRGB_TAGTOOL_SUCSESS'));
		}

		$redirect_url = JRoute::_('index.php?option=com_ukrgb&view=tagtool', false);
		$this->setRedirect($redirect_url);
	}
}
