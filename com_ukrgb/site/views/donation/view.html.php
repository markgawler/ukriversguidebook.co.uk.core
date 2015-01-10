<?php

/**
 * @version		0.1
 * @package		UKRGB - Donation
 * @copyright	Copyright (C) 2012 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Ukrgb Component
 */
class UkrgbViewDonation extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$status = $this->get('TransactionStatus');
		//echo "Status:"; 
		//var_dump($status);
		switch ($status)
		{
		case UkrgbTxState::Good:
			
			$this->status = $this->get('PaymentStatus');
			if ($status = 'Complete')
			{
				$this->value = $this->get('Amount');
				$this->name = $this->get('Name');
				$this->returnUrl = "/forum/viewforum.php?f=" . $this->get('ForumId');
				$this->linkText = $this->get('ForumName');
				
				// Check for errors.
				if (count($errors = $this->get('Errors')))
				{
					JError::raiseError(500, implode('<br />', $errors));
					return false;
				}
			}
			else
			{
				$tpl = 'cancel';
			}
			break;
		case UkrgbTxState::Error:
			$tpl = 'problem';
			$this->errorMsg = $this->get('ErrorMsg');
			$this->responceCode = $this->get('ResponceCode');
			break;
	
		case UkrgbTxState::None:
			$tpl = 'no_transaction';
			break;
		}
		// Display the view
		parent::display($tpl);
	}
}
