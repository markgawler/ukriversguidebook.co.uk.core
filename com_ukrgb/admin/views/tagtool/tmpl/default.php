<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

?>
<script type="text/javascript">
	Joomla.submitbutton = function()
	{
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.url_list.value == ""){
			alert("<?php echo JText::_('COM_UKRGB_TAGTOOL_PLEASE_SELECT_A_FILE', true); ?>");
		}
		else
		{
			jQuery('#loading').css('display', 'block');
			form.submit();
		}
	};
</script>

<div id="installer-install" class="clearfix">
<form enctype="multipart/form-data"  action="<?php echo JRoute::_('index.php?option=com_ukrgb&view=tagtool');?>" 
method="post" name="adminForm" id="adminForm" class="form-horizontal">

<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>

	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'upload')); ?>
		
	
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload', JText::_('COM_UKRGB_TAGTOOL_TAB', true)); ?>
	<fieldset class="uploadform">
		<legend><?php echo JText::_('COM_UKRGB_TAGTOOL_TAB_TITLE'); ?></legend>
		<?php echo $this->form->renderFieldset('tagfieldset')?>
		
		
		<div class="control-group">
		
			<label for="url_list" class="control-label"><?php echo JText::_('COM_UKRGB_TAGTOOL_UPLOAD_FILE'); ?></label>
			<div class="controls">
				<input class="input_box" id="url_list" name="url_list" type="file" size="57" />
			</div>
		</div>
		
		<div class="form-actions">
			<input class="btn btn-primary" type="button" value="<?php echo JText::_('COM_UKRGB_UPLOAD_AND_TAG'); ?>" onclick="Joomla.submitbutton()" />
		</div>
	</fieldset>
	
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<input type="hidden" name="type" value="" />
	<input type="hidden" name="task" value="tagtool.upload" />
	<?php echo JHtml::_('form.token'); ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
</form>
</div>

