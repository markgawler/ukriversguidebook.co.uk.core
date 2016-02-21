<?php
/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die();

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

//As we are using the com_content Layout we need the language file (I think).
JFactory::getLanguage()->load('com_content');

// Create shortcuts to some parameters.
$params  = $this->event->params;
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);

JHtml::_('behavior.caption');
?>
<div class="item-page" itemscope itemtype="http://schema.org/Event">
	<meta itemprop="inLanguage" content="<?php echo ($this->event->language === '*') ? JFactory::getConfig()->get('language') : $this->event->language; ?>" />

	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
	

	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_author') ); ?>

	<?php if (!$useDefList && $this->print) : ?>
		<div id="pop-print" class="btn hidden-print">
			<?php echo JHtml::_('icon.print_screen', $this->event, $params); ?>
		</div>
		<div class="clearfix"> </div>
	<?php endif; ?>
	<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
	<div class="page-header">
		<h2 itemprop="name">
			<?php if ($params->get('show_title')) : ?>
				<?php echo $this->escape($this->event->title); ?>
			<?php endif; ?>
		</h2>
		<?php if ($this->event->state == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
		<?php if (strtotime($this->event->publish_up) > strtotime(JFactory::getDate())) : ?>
			<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
		<?php endif; ?>
		<?php if ((strtotime($this->event->publish_down) < strtotime(JFactory::getDate())) && $this->event->publish_down != JFactory::getDbo()->getNullDate()) : ?>
			<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php if (!$this->print) : ?>
		<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
			<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->event, 'print' => false)); ?>
		<?php endif; ?>
	<?php else : ?>
		<?php if ($useDefList) : ?>
			<div id="pop-print" class="btn hidden-print">
				<?php echo JHtml::_('icon.print_screen', $this->event, $params); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	

	<?php if ($params->get('access-view')):?>	
		<div>
			<h4><?php echo $this->event->summary; ?></h4>
		</div>
		
		<div>
			<span class="icon-location"></span>
			<meta itemprop="location" itemscope itemtype="http://schema.org/Place">
			<strong><?php echo JText::_('COM_UKRGB_EVENT_LOCATION') . ': '; ?></strong> <?php echo $this->event->location; ?>
		</div>
		
		<div>
			<span class="icon-calendar"></span>
			<meta itemprop="startDate" datetime="<?php echo JHtml::_('date', $this->event->start_date, 'c'); ?>">
			<strong><?php echo JText::_('COM_UKRGB_EVENT_DATE') . ': ' ?></strong>
 			<?php echo JHtml::_('date', $this->event->start_date, JText::_('DATE_FORMAT_LC3')); ?>
		</div>
		<?php if ($this->event->end_date >  $this->event->start_date):?>
			<div>
				<span class="icon-calendar"></span>
				<meta itemprop="endDate" datetime="<?php echo JHtml::_('date', $this->event->end_date, 'c'); ?>">
				<strong><?php echo JText::_('COM_UKRGB_EVENT_END_DATE') . ': ' ?></strong>
	 			<?php echo JHtml::_('date', $this->event->end_date, JText::_('DATE_FORMAT_LC3')); ?>
			</div>
		<?php endif?>
		<div>
		<?php echo $this->event->description; ?>
		</div>
	<?php endif; ?>
	<?php if ($useDefList) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->event, 'params' => $params, 'position' => 'above')); ?>
	<?php endif; ?>
	
</div>
