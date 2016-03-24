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

//$n         = count($this->calendars);
$params = $this->params;

?>

<?php if (!empty($this->calendars)) : ?>		
	<table class="category table table-striped table-bordered table-hover">
		<?php
		$headerTitle    = 'headers="categorylist_header_title"';
		$headerDesc = 'headers="categorylist_header_desc"';
		$headerEdit     = 'headers="categorylist_header_edit"';
		?>
		<thead>
			<tr>
				<th id="categorylist_header_title">
					<?php echo JText::_('JGLOBAL_TITLE'); ?>
				</th>
				<th id="categorylist_header_desc">
					<?php echo JText::_('COM_UKRGB_CAL_DESC'); ?>
				</th>
				<?php if ($this->canCreate) : ?>
					<th id="categorylist_header_edit"><?php echo JText::_('COM_CONTENT_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->calendars as $i => $cal) : ?>
				<tr class="cat-list-row<?php echo $i % 2; ?>" >
					<td <?php echo $headerTitle; ?> class="list-title">
						<a href="<?php echo JRoute::_(UkrgbHelperRoute::getCalendarRoute($cal->slug, $cal->catid)); ?>">
								<?php echo $this->escape($cal->title); ?>
						</a>
					</td>
					<td <?php echo $headerDesc; ?> class="">
								<?php echo $this->escape($cal->description); ?>
					</td>
					<?php if ($this->canCreate) : ?>
						<td <?php echo $headerEdit; ?> class="list-edit">
							<?php if ($cal->params->get('access-create')) : ?>
								<?php echo JHtml::_('icon.create', $this->cat[$cal->catid], $params); ?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>