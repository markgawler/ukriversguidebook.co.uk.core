<?php
/**
* UKRGB Custom Search Module Entry Point
*
* @package    UKRGB
* @subpackage Modules
* @license    GNU/GPL, see LICENSE.php
*/

// No direct access
defined('_JEXEC') or die;

$layout = $params->get('layout', 'default');
$mode = $params->get('Mode',null);
if ($mode == '0')
{
	$layout = $layout . '_result';
}
require JModuleHelper::getLayoutPath('mod_ukrgbcustomsearch', $layout);
