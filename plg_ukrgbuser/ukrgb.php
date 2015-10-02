<?php
/**
 * @package		Joomla
 * @subpackage	User
 * @copyright	Copyright (C) 2015 Mark Gawler. All rights reserved.
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgUserUkrgb extends JPlugin {
	/**
	 * Plugin to prompt user to update email address 
	 *
	 */
	public function onUserAfterLogin($options)
	{	
		$session =& JFactory::getSession();
		$session->set( 'ukrgbUpdateEmail', True );

		
		
	}
}
