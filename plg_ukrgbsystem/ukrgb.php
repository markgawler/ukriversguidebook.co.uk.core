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

class plgSystemUkrgb extends JPlugin {
	/**
	 * Plugin to prompt email change and load AWS library
	 * 
	 */
	protected $autoloadLanguage = true;
	
	/**
	* Method to register custom library.
	*
	* return  void
	*/
	public function onAfterInitialise()
	{
		require JPATH_LIBRARIES .'/ukrgbaws' . '/vendor/autoload.php';
	}
	
	/**
	* Befor Render add the PayPal donate button to forum pages
	**/
	public function onBeforeRender()
	{	
		$app = JFactory::getApplication();
		if ($app->isSite()){
			
			// Display update email message if a bounce has been detected and we are not updating the user profile
			$session = JFactory::getSession();
			if ($session->get('ukrgbUpdateEmail') and $app->input->getCmd('option','') != 'com_users')
			{					
				$app->enqueueMessage(JText::_('PLG_SYSTEM_UKRGB_UPDATE_EMAIL').' <a href="'. JRoute::_('index.php?option=com_users&lang=en&layout=edit&view=profile').'">Update Profile</a>');
			}
		}
	}
}
