<?php
/**
 * @version		0.1
 * @package		UKRGB - Donation
 * @copyright	Copyright (C) 2012-2014 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h1>Thankyou</h1>

<h3>Thank you <?php echo $this->name; ?> for your donation of <?php echo $this->value; ?></h3>
<p>Your transaction has been completed and a receipt for your donation has been sent to you. 
You may log into your account at www.paypal.com to view details of this transaction.</p>
<p>Thank you.</p>
<p>The Management.</p>
<p></p>
<p> Return to the <a href="/forum">Community Forum</a> - <a href="<?php echo $this->returnUrl;?>"><?php echo $this->linkText;?></a>




