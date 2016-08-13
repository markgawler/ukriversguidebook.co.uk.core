<?php
/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

//As we are using the com_content Layout we need the language file (I think).
JFactory::getLanguage()->load('com_content');

// Create some shortcuts.
$params    = &$this->event->params;
$n         = count($this->events);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

// Check for at least one editable article
$isEditable = false;

if (!empty($this->events))
{
	foreach ($this->events as $event)
	{
		if ($event->params->get('access-edit'))
		{
			$isEditable = true;
			break;
		}
	}
	$isEditable = true;
}
?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">

<?php if (empty($this->events)) : ?>
	<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
<?php endif; ?>


	<fieldset class="filters btn-toolbar clearfix">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search">
					<?php echo JText::_('COM_UKRGB_' . $this->params->get('filter_field') . '_FILTER_LABEL') . '&#160;'; ?>
				</label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_CONTENT_' . $this->params->get('filter_field') . '_FILTER_LABEL'); ?>" />
			</div>
		<?php endif; ?>
		
<?php if (!empty($this->events)) : ?>		
		
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
<?php endif;?>
	</fieldset>
<?php if (!empty($this->events)) : ?>		
	<table class="category table table-striped table-bordered table-hover">
		<?php
		$headerTitle    = 'headers="categorylist_header_title"';
		$headerDate     = 'headers="categorylist_header_date"';
		$headerAuthor   = 'headers="categorylist_header_author"';
		$headerEdit     = 'headers="categorylist_header_edit"';
		?>
		<thead>
			<tr>
				<th id="categorylist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<?php if ($date = $this->params->get('list_show_date')) : ?>
					<th id="categorylist_header_date">
						<?php if ($date == "created") : ?>
							<?php echo JHtml::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.created', $listDirn, $listOrder); ?>
						<?php elseif ($date == "modified") : ?>
							<?php echo JHtml::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.modified', $listDirn, $listOrder); ?>
						<?php elseif ($date == "published") : ?>
							<?php echo JHtml::_('grid.sort', 'COM_CONTENT_' . $date . '_DATE', 'a.publish_up', $listDirn, $listOrder); ?>
						<?php endif; ?>
					</th>
				<?php endif; ?>
				<?php if ($this->params->get('list_show_author')) : ?>
					<th id="categorylist_header_author">
						<?php echo JHtml::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
					</th>
				<?php endif; ?>
				<?php if ($isEditable) : ?>
					<th id="categorylist_header_edit"><?php echo JText::_('COM_UKRGB_CREATE_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->events as $i => $event) : ?>
				<?php if ($this->events[$i]->state == 0) : ?>
				<tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
				<?php else: ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
				<?php endif; ?>
					<td <?php echo $headerTitle; ?> class="list-title">
						

							<a href="<?php echo JRoute::_(UkrgbHelperRoute::getEventRoute($event->slug, $event->catid, $event->language)); ?>">
								<?php echo $this->escape($event->title); ?>
							</a>
						
						<?php if ($event->state == 0) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JUNPUBLISHED'); ?>
							</span>
						<?php endif; ?>
						<?php if (strtotime($event->publish_up) > strtotime(JFactory::getDate())) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JNOTPUBLISHEDYET'); ?>
							</span>
						<?php endif; ?>
						<?php if ((strtotime($event->publish_down) < strtotime(JFactory::getDate())) && $event->publish_down != JFactory::getDbo()->getNullDate()) : ?>
							<span class="list-published label label-warning">
								<?php echo JText::_('JEXPIRED'); ?>
							</span>
						<?php endif; ?>
					</td>
					<?php if ($this->params->get('list_show_date')) : ?>
						<td <?php echo $headerDate; ?> class="list-date small">
							<?php
							echo JHtml::_(
								'date', $event->displayDate,
								$this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))
							); ?>
						</td>
					<?php endif; ?>
					<?php if ($this->params->get('list_show_author', 1)) : ?>
						<td <?php echo $headerAuthor; ?> class="list-author">
							<?php if (!empty($event->author) || !empty($event->created_by_alias)) : ?>
								<?php $author = $event->author ?>
								<?php $author = ($event->created_by_alias ? $event->created_by_alias : $author);?>
								<?php if (!empty($event->contact_link) && $this->params->get('link_author') == true) : ?>
									<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', $event->contact_link, $author)); ?>
								<?php else: ?>
									<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					
					<?php if ($isEditable) : ?>
						<td <?php echo $headerEdit; ?> class="list-edit">
							<?php if ($event->params->get('access-edit')) : ?>
								<?php echo JHtml::_('icon.edit', $event, $params); ?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->events)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; ?>
