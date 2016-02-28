<?php
/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Ukrgb Component Category Tree
 *
 */
class UkrgbCategories extends JCategories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   11.1
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__ukrgb_events';
		$options['extension'] = 'com_ukrgb';

		parent::__construct($options);
	}
}
