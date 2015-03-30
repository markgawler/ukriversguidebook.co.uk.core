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
	 * Plugin to add the PayPal donate button to forum pages 
	 *
	 */
	public function onBeforeRender()
	{	
		
		$app = JFactory::getApplication();
		if ($app->isSite()){
			
			$active_menu = $app->getMenu()->getActive();
			
			if (!is_null($active_menu) && $active_menu->id == $this->params->get('forummenuitem')){
				$uri=JFactory::getURI();
				if ($uri->hasVar("f") && !$uri->hasVar("p")){
					$forum = $uri->getVar("f");
					$selected_forums = $this->params->get('selected_forums');
					
					if (is_array($selected_forums)) {
						if (in_array($forum, $selected_forums)){
							jimport('joomla.application.component.helper');
							$config = JComponentHelper::getParams('com_ukrgb');
							if ($config->get('sandbox')){
								$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
								$hosted_button_id = $config->get('sandbox_hosted_button_id');
							}else{
								$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
								$hosted_button_id = $config->get('hosted_button_id');
							}
							$item_name = $forum.':UK Rivers Controbution';
							$script = <<<SCRIPT
jQuery(document).ready(function(){jQuery("#paypal-box").append('<form action="{$paypal_url}" method="post">\
<input type="hidden" name="cmd" value="_s-xclick">\
<input type="hidden" name="hosted_button_id" value="{$hosted_button_id}">\
<input type="hidden" name="custom" value="UK Rivers Guidebook">\
<input type="hidden" name="item_name" value="{$item_name}">\
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal">\
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">\
</form>')});
SCRIPT;
  							JFactory::GetDocument()->addScriptDeclaration($script);
						}
					}
				}
			}
		}
	}
}
