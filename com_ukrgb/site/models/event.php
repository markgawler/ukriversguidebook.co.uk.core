<?php
/**
 * @package		UKRGB - Event
 * @copyright	Copyright (C) 2016 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

use Joomla\Registry\Registry;


/**
 * UKRGB Component Event Model
 *
 */
class UkrgbModelEvent extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_ukrgb.event';
	
	public function getTable($type = 'Event', $prefix = 'UkrgbTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('event.id', $pk);
	
	
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	
		// TODO: Tune these values based on other permissions.
		$user = JFactory::getUser();
	
		if ((!$user->authorise('core.edit.state', 'com_ukrgb')) && (!$user->authorise('core.edit', 'com_ukrgb')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	public function getItem($pk = null)
	{
		$user = JFactory::getUser();
		
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('event.id');
		if ($this->_item === null)
		{
			$this->_item = array();
		}
		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
				->select(
						$this->getState(
								'event.select', 'a.id, a.title, a.alias, a.summary, a.description, a.location, a.start_date, a.end_date, ' .
								// If badcats is not null, this means that the article is inside an unpublished category
								// In this case, the state is set to 0 to indicate Unpublished (even if the article state is Published)
								'CASE WHEN badcats.id is null THEN a.state ELSE 0 END AS state, ' .
								'a.catid, a.created, a.created_by, a.created_by_alias, ' .
								// Use created if modified is 0
								'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
								'a.modified_by, a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, ' .
								'a.version, a.ordering, ' .
								'a.access, a.hits, a.language, a.xreference'
								)
						);
				$query->from('#__ukrgb_events AS a');
		
				// Join on category table.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access')
				->join('LEFT', '#__categories AS c on c.id = a.catid');
		
				// Join on user table.
				$query->select('u.name AS author')
				->join('LEFT', '#__users AS u on u.id = a.created_by');
		
				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				}
		
				// Join over the categories to get parent category titles
				$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
				->join('LEFT', '#__categories as parent ON parent.id = c.parent_id')
		
				->where('a.id = ' . (int) $pk);
		
				if ((!$user->authorise('core.edit.state', 'com_ukrgb')) && (!$user->authorise('core.edit', 'com_ukrgb')))
				{
					// Filter by start and end dates.
					$nullDate = $db->quote($db->getNullDate());
					$date = JFactory::getDate();
		
					$nowDate = $db->quote($date->toSql());
		
					$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
					->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
				}
		
				// Join to check for category published state in parent categories up the tree
				// If all categories are published, badcats.id will be null, and we just use the article state
				$subquery = ' (SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
				$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
				$subquery .= 'WHERE parent.extension = ' . $db->quote('com_ukrgb');
				$subquery .= ' AND parent.published <= 0 GROUP BY cat.id)';
				$query->join('LEFT OUTER', $subquery . ' AS badcats ON badcats.id = c.id');
		
				// Filter by published state.
				$published = $this->getState('filter.published');
				$archived = $this->getState('filter.archived');
		
				if (is_numeric($published))
				{
					$query->where('(a.state = ' . (int) $published . ' OR a.state =' . (int) $archived . ')');
				}
		
				$db->setQuery($query);
		
				$data = $db->loadObject();
		
				if (empty($data))
				{
					return JError::raiseError(404, JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));
				}
		
				// Check for published state if filter set.
				if (((is_numeric($published)) || (is_numeric($archived))) && (($data->state != $published) && ($data->state != $archived)))
				{
					return JError::raiseError(404, JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));
				}
		
				// Convert parameter fields to objects.
				$registry = new Registry;
				//$registry->loadString($data->attribs);
		
				$data->params = clone $this->getState('params');
				$data->params->merge($registry);
		
		
				// Technically guest could edit an article, but lets not check that to improve performance a little.
				if (!$user->get('guest'))
				{
					$userId = $user->get('id');
					$asset = 'com_ukrgb.event.' . $data->id;
		
					// Check general edit permission first.
					if ($user->authorise('core.edit', $asset))
					{
						$data->params->set('access-edit', true);
					}
		
					// Now check if edit.own is available.
					elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
					{
						// Check for a valid user and that they are the owner.
						if ($userId == $data->created_by)
						{
							$data->params->set('access-edit', true);
						}
					}
				}
		
				// Compute view access permissions.
				if ($access = $this->getState('filter.access'))
				{
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				}
				else
				{
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();
		
					if ($data->catid == 0 || $data->category_access === null)
					{
						$data->params->set('access-view', in_array($data->access, $groups));
					}
					else
					{
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}
		
				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}
		
		return $this->_item[$pk];
	}
		


}
