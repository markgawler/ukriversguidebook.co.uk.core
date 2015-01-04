<?php
/**
 * @package		Joomla
 * @subpackage	System
 * @copyright	Copyright (C) 2015 Mark Gawler. All rights reserved.
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemUkrgbDonate extends JPlugin {
	/**
	 * Plugin to add the PayPal donate button to forum pages 
	 *
	 */
	public function onBeforeRender()
	{	
		//$document=JFactory::getDocument();
		//$uri=JFactory::getURI();
		//error_log("-- ".$uri);
		$app = JFactory::getApplication();
		if ($app->isSite()){
			$menu = $app->getMenu()->getActive()->id;
			if ($menu == $this->params->get('forummenuitem')){
				//error_log("---- Forum Render --");
				$uri=JFactory::getURI();
				if ($uri->hasVar("f")){
					$forum = $uri->getVar("f");
				}
			}
		}
	}
}
