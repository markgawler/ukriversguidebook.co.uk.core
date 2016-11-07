<?php
/**
 * This is the jfusion Forumlist field file
 *
 * PHP version 5
 *
 * @category  JFusion
 * @package   Fields
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
/**
 * Require the Jfusion plugin factory
 */
require_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jfusion' . DIRECTORY_SEPARATOR . 'import.php';
/**
 * JFusion Field class Forumlist
 *
 * @category  JFusion
 * @package   Fields
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
class JFormFieldForumlist extends JFormField
{
	public $type = 'ForumList';
	/**
	 * Get an field
	 *
	 * @return string html
	 */
	protected function getInput()
	{
		try {
			$db = JFactory::getDBO();

			$query = $db->getQuery(true)
				->select('params')
				->from('#__extensions')
				->where('element = ' . $db->quote('com_ukrgb'))
				->where('type = ' . $db->quote('component'));

			$db->setQuery($query);
			$params = $db->loadResult();
			$parametersInstance = new JRegistry($params);
			
			//load custom plugin parameter
			$jPluginParamRaw = unserialize(base64_decode($parametersInstance->get('JFusionPluginParam')));
			$jname = $jPluginParamRaw['jfusionplugin'];

			if (!empty($jname)) {
				$JFusionPlugin = JFusionFactory::getForum($jname);
				if ($JFusionPlugin->isConfigured()) {
					if (method_exists($JFusionPlugin, 'getForumList')) {
						$forumlist = $JFusionPlugin->getForumList();
						if (!empty($forumlist)) {
							$selectedValue = $parametersInstance->get($this->fieldname);
							$output = JHTML::_('select.genericlist', $forumlist, $this->formControl . '[' . $this->fieldname . '][]', 'multiple size="6" class="inputbox"', 'id', 'name', $selectedValue);
						} else {
							throw new RuntimeException($jname . ': ' .JText::_('NO_LIST'));
						}
					} else {
						throw new RuntimeException($jname . ': ' .JText::_('NO_LIST'));
					}
				} else {
					throw new RuntimeException($jname . ': ' .JText::_('NO_VALID_PLUGINS'));
				}
			} else {
				throw new RuntimeException(JText::_('NO_PLUGIN_SELECT'));
			}
		} catch(Exception $e) {
			$output = '<span style="float:left; margin: 5px 0; font-weight: bold;">' . $e->getMessage() . '</span>';
		}
		return $output;
	}
}
