<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class TodolistModelItems extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.`id`',
                'state', 'a.`state`',
                'ordering', 'a.`ordering`',
                'status', 'a.`status`',
                'created_by', 'a.`created_by`',
                'modified_by', 'a.`modified_by`',
                'title', 'a.`title`',
                'catid', 'a.`catid`'
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        $this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search'));

        $this->setState('filter.state', $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string'));

        $this->setState('filter.catid', $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid', '', 'string'));

        $params = JComponentHelper::getParams('com_todolist');
        $this->setState('params', $params);

        parent::populateState('a.title', 'asc');
    }

    protected function getListQuery()
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('DISTINCT a.*');
        $query->from('`#__todolist_items` AS a');

        $query->select('`u`.name AS `created_by_name`');
        $query->join('LEFT', '#__users AS `u` ON `u`.id = a.`created_by`');

        $query->select('`m`.name AS `modified_by_name`');
        $query->join('LEFT', '#__users AS `m` ON `m`.id = a.`modified_by`');

        $query->select('`c`.title AS `catid`');
        $query->join('LEFT', '#__categories AS `c` ON `c`.id = a.`catid`');

        $state = $this->getState('filter.state');

        if (is_numeric($state))
        {
            $query->where('a.state = ' . (int) $state);
        }
        elseif ($state === '')
        {
            $query->where('(a.state IN (0, 1))');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }
            else
            {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.title LIKE ' . $search . ' )');
            }
        }

        $filter_catid = $this->state->get('filter.catid');
        if ($filter_catid)
        {
            $query->where("a.`catid` = '".$db->escape($filter_catid)."'");
        }

        $orderCol  = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        if ($orderCol && $orderDirn)
        {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

}
