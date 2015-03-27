<?php
/**
* @package     Joomla.Administrator
* @subpackage  com_ukrgb
*
* @copyright   Copyright (C) 2015 - 2015 Mark Gawler, Inc. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 */
class UkrgbTableRiverguide extends JTable
{
	/**
	 * @param   JDatabaseDriver  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__ukrgb_riverguide', 'id', $db);
	}
}
