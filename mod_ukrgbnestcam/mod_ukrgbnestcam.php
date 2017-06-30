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

//$layout = $params->get('layout', 'default');
$url = $params->get('Srcurl',null);

require JModuleHelper::getLayoutPath('mod_ukrgbnestcam', $layout);
