<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class TodolistModelItems extends JModelList
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app  = JFactory::getApplication();
        $list = $app->getUserState($this->context . '.list');

        $list['limit']     = (int) JFactory::getConfig()->get('list_limit', 20);
        $list['start']     = $app->input->getInt('start', 0);
        $list['ordering']  = $ordering;
        $list['direction'] = $direction;

        $app->setUserState($this->context . '.list', $list);
        $app->input->set('list', null);

        parent::populateState($ordering, $direction);
    }

    protected function getListQuery()
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*, created_by.name as created_by_name, modified_by.name as modified_by_name, c.title AS cat_title')
              ->from('`#__todolist_items` AS a')
              ->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by')
              ->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by')
              ->join('LEFT', '#__categories AS c ON c.id = a.catid');
        
        if (!JFactory::getUser()->authorise('core.edit', 'com_todolist'))
        {
            $query->where('a.state = 1');
        }

        $query->order('a.ordering ASC');

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();

        // Do something with the items here.

        return $items;
    }
}
