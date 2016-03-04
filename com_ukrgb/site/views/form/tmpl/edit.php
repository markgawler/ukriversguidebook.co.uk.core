<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->configFieldsets  = array('editorConfig');
$this->hiddenFieldsets  = array('basic-limited');
$this->ignore_fieldsets = array('jmetadata', 'item_associations');

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);
$editoroptions = isset($params->show_publishing_options);

//var_dump($this);die('this');
?>

<form action="<?php echo JRoute::_('index.php?option=com_ukrgb&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_CONTENT_ARTICLE_CONTENT', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="form-vertical ">
					<?php echo $this->form->getControlGroup('location'); ?>
					<?php echo $this->form->getControlGroup('summary'); ?>
					<?php echo $this->form->getControlGroup('description'); ?>
				</div>
			</div>
			<div class="span3">
				<fieldset class="form-vertical">
					<?php echo $this->form->getControlGroup('start_date'); ?>
					<?php echo $this->form->getControlGroup('end_date'); ?>
					<?php echo $this->form->getControlGroup('calid'); ?>
				</fieldset>
				<?php //echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>


		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php //if ($this->canDo->get('core.admin')) : ?>
			<?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'editor', JText::_('COM_CONTENT_SLIDER_EDITOR_CONFIG', true)); ?>
			<?php //echo $this->form->renderFieldset('editorConfig'); ?>
			<?php //echo JHtml::_('bootstrap.endTab'); ?>
		<?php //endif; ?>

		<?php //if ($this->canDo->get('core.admin')) : ?>
			<?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_CONTENT_FIELDSET_RULES', true)); ?>
				<?php //echo $this->form->getInput('rules'); ?>
			<?php //echo JHtml::_('bootstrap.endTab'); ?>
		<?php //endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
		<?php echo JHtml::_('form.token'); ?>


		</div>
</form>
