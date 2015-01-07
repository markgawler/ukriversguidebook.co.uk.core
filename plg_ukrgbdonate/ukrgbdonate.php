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
		
		$app = JFactory::getApplication();
		if ($app->isSite()){

			$menu = $app->getMenu()->getActive()->id;
			if ($menu == $this->params->get('forummenuitem')){
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
							$item_name = $forum.':xxxx';
							$script1 = <<<'EOD1'
jQuery(document).ready(function(){jQuery("#paypal-box").append('<form action="
EOD1;
//$paypal_url
							$script2 = <<<'EOD2'
" method="post">\
<input type="hidden" name="cmd" value="_s-xclick">\
<input type="hidden" name="hosted_button_id" value="
EOD2;
//$hosted_button_id
							$script3 = <<<'EOD3'
">\
<input type="hidden" name="custom" value="UK Rivers Guidebook">\
<input type="hidden" name="item_name" value="
EOD3;
//$item_name
//0:FORUM_NAME
							$script4 = <<<'EOD4'
">\
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal">\
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">\
</form>')});
EOD4;
  							JFactory::GetDocument()->addScriptDeclaration($script1.$paypal_url.$script2.$hosted_button_id.$script3.$item_name.$script4);
						}
					}
				}
			}
		}
	}
}
