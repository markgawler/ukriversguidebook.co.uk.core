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
		$cats = $config->get('riverguidecats');
		
		if (!empty($cats) && in_array($catid, $cats))
		{
			$result = array(
					$config->get('dificultytags0')[0],
					$config->get('dificultytags1')[0],
					$config->get('dificultytags2')[0],
					$config->get('dificultytags3')[0]
			);
			return $result;
		}
		return array();	
	}
}