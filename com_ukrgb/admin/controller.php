<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of Ukrgb component
*/
class UkrgbController extends JControllerLegacy
{
	protected $default_view = 'donation';
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false)
	{
		$view   = $this->input->get('view', 'donation');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		parent::display($cachable);
		return $this;
	}

}
