<?php
/**
* @package     joomla.helper
* @subpackage  com_ukrgb
*
* @copyright   Copyright (C) 2014 - 2015 Mark Gawler. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('_JEXEC') or die;


abstract class RiverguideHelper
{
	/**
	 * Returns the set of tags assosciated with river guides dificulty if the category is a river guide cat
	 * a null array is returned for non-river guide categories
	 *  
	 * @param unknown $catid
	 * @return array()
	 */
	public static function get_tagset_for_category($catid)
	{
		jimport('joomla.application.component.helper');
		$config = JComponentHelper::getParams('com_ukrgb');
		
		if (in_array($catid, $config->get('riverguidecats')))
		{
			return $config->get('tags');
		}
		else
		{
			return array();	
		}
	}
}