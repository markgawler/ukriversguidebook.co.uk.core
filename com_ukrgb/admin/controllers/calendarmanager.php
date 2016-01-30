<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Calandar list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 */
class UkrgbControllerCalandarManager extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Calandar', $prefix = 'UkrgbModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * Method to provide child classes the opportunity to process after the delete task.
	 *
	 * @param   JModelLegacy   $model   The model for the component
	 * @param   mixed          $ids     array of ids deleted.
	 *
	 * @return  void
	 */
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}
}
