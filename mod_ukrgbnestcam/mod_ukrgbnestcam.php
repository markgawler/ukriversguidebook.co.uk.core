<?php
/**
* UKRGB Custom Video Module Entry Point
*
* @package    UKRGB
* @subpackage Modules
* @license    GNU/GPL, see LICENSE.php
*/

// No direct access
defined('_JEXEC') or die;
$layout = $params->get('layout', 'default');
$url = $params->get('Srcurl',null);
$id = $app->input->get('id',0,'int');
$allowedId = $params->get('Articleid', '0');
if ($allowedId == 0 or $allowedId == $id){
	require JModuleHelper::getLayoutPath('mod_ukrgbnestcam', $layout);
}