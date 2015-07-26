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
	 * Returns the set of tags assosciated with river guides grade if the category is a river guide cat
	 * a null array is returned for non-river guide categories
	 *  
	 * @param unknown $catid
	 * @return array()
	 */
	/*public static function get_tagset_for_category($catid)
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
	}*/
	
	/**
	 * Returns Tru is the category is a tiverguide category
	 * 
	 * @param unknown $catid
	 * @return boolean
	 */
	public static function is_riverguide_category ($catid) {
		jimport('joomla.application.component.helper');
		$config = JComponentHelper::getParams('com_ukrgb');
		$cats = $config->get('riverguidecats');
		
		return !empty($cats) && in_array($catid, $cats);
	}
	
	/**
	 * Returns an objet of two arrays:
	 *  ->guides[id] is an array indexed by the article id, each element 
	 * 		is an object containing the river summary and the grade
	 *  ->grade_count[grade] is an array indexed by the grade coontaining a 
	 *      count of guides at the grade
	 *
	 * @param unknown $catid
	 * @return StdClass|boolean
	 */
	public static function get_riverguides_for_category($catid){
		
		$db = JFactory::getDBO();
		
		$query = $db->getQuery(true)
		->select(array('id','summary','grade'))
		->from('#__ukrgb_riverguide')
		->where('catid  = ' . $catid);
				
		$db->setQuery($query);
		$r = $db->loadObjectList();
		if (!empty($r))
		{
			$grade = array(0,0,0,0);
			$res = array();
			foreach ($r as $item){
				$res[$item->id] = $item;
				$grade[$item->grade] = $grade[$item->grade] +1;
			}
			ksort($grade);
			$result = new StdClass;
			$result->guides = $res;
			$result->grade_count = $grade;

			return $result;
		} 
		$result = new StdClass;
		$result->guides = array();
		$result->grade_count = array();
		return $result;
	}
	
	
}