<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');


if (!JFactory::getUser()->authorise('core.manage', 'com_ukrgb'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$controller	= JControllerLegacy::getInstance('Ukrgb');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();