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
 * Routing class from com_ukrgb
 *
 * @since  3.3
 */
class UkrgbRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_ukrgb component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		//var_dump($query);die('query');
		$segments = array();

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $this->menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem = $this->menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_ukrgb')
		{				
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']))
		{
			$view = $query['view'];
		}
		else
		{
			// We need to have a view in the query or it is an invalid URL
			return $segments;
		}

		if ($view == 'event')
		{
			if (!$menuItemGiven)
			{
				$segments[] = $view;
			}

			unset($query['view']);

			
			if (isset($query['id']))
			{
				// Make sure we have the id and the alias
				if (strpos($query['id'], ':') === false)
				{
					$db = JFactory::getDbo();
					$dbQuery = $db->getQuery(true)
						->select('alias')
						->from('#__ukrgb_events')
						->where('id=' . (int) $query['id']);
					$db->setQuery($dbQuery);
					$alias = $db->loadResult();
					$query['id'] = $query['id'] . ':' . $alias;
				}
			}
			else
			{
				// We should have these two set for this view.  If we don't, it is an error
				return $segments;
			}
			$segments[] = $query['id'];
			unset($query['id']);

		}		

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		//var_dump($segments);die('segments');
		$vars = array();
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		// Count segments
		$count = count($segments);
		// Handle View and Identifier
		switch ($item->query['view'])
		{
			case 'events':
				$id = explode(':', $segments[$count-1]);
				$vars['id'] = (int) $id[0];
				$vars['view'] = 'event';
				break;
				
			case 'calendar':
				$id = explode(':', $segments[$count-1]);
				$vars['id'] = (int) $id[0];
				$vars['view'] = 'calendar';
				break;
		}
		return $vars;
		
	}
}
/**
 * UKRGB router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function ukrgbBuildRoute(&$query)
{
	$router = new UkrgbRouter;

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function ukrgbParseRoute($segments)
{
	$router = new UkrgbRouter;

	return $router->parse($segments);
}
