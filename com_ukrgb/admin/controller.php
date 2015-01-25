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
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', 'main');
		error_log($view);
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		parent::display();
		return $this;
	}

}
