<?php
// No direct access to this file
defined('_JEXEC') or die;

// import Joomla controller library
//jimport('joomla.application.component.controller');

/**
 * General Controller of Ukrgb component
*/
class UkrgbController extends JControllerLegacy
{
	protected $default_view = 'main';
	/**
	 * Method to display a view.
	 *
	 * @param   boolean			$cachable	If true, the view output will be cached
	 * @param   array  $urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.
	
	 */
	function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/ukrgb.php';
		
		$view   = $this->input->get('view', 'main');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		
		//$view   = $this->input->get('view', 'eventmanager');
		//$layout = $this->input->get('layout', 'default');
		//$id     = $this->input->getInt('id');
		
		// Check for edit form.
		if ($view == 'event' && $layout == 'edit' && !$this->checkEditId('com_ukrgb.edit.event', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_ukrgb&view=eventmanager', false));
		
			return false;
		}
		
		
		parent::display();
		return $this;
	}

}
