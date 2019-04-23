<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

use Joomla\Utilities\ArrayHelper;

class TodolistModelItem extends JModelItem
{
    protected function populateState()
    {
        $app  = JFactory::getApplication('com_todolist');
        $user = JFactory::getUser();

        // Check published state
        if ((!$user->authorise('core.edit.state', 'com_todolist')) && (!$user->authorise('core.edit', 'com_todolist')))
        {
            $this->setState('filter.published', 1);
            $this->setState('fileter.archived', 2);
        }

        $id = JFactory::getApplication()->input->get('id');

        $this->setState('item.id', $id);

        // Load the parameters.
        $params       = $app->getParams();
        $params_array = $params->toArray();

        if (isset($params_array['item_id']))
        {
            $this->setState('item.id', $params_array['item_id']);
        }

        $this->setState('params', $params);
    }

    public function &getData($id = null)
    {
        if ($this->_item === null)
        {
            $this->_item = false;

            if (empty($id))
            {
                $id = $this->getState('item.id');
            }

            // Get a level row instance
            $table = $this->getTable('Item', 'TodolistTable');

            // Attempt to load the row
            if ($table->load($id))
            {
                // Check published state.
                if ($published = $this->getState('filter.published'))
                {
                    if ($table->state != $published)
                    {
                        throw new Exception(JText::_('COM_TODOLIST_ITEM_NOT_LOADED'), 403);
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties  = $table->getProperties(1);
                $this->_item = ArrayHelper::toObject($properties, 'JObject');
            }
        }

        if (isset($this->_item->created_by) )
        {
            $this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
        }

        if (isset($this->_item->modified_by) )
        {
            $this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('title')
              ->from('#__categories')
              ->where('id = ' . $this->_item->catid);

        $db->setQuery($query);

        $this->_item->cat_title = $db->loadResult();
        if(empty($this->_item->cat_title)) {
            $this->_item->cat_title = $item->catid;
        }

        return $this->_item;
    }

    public function complete($id, $status)
    {
        $user  = JFactory::getUser();

        if ($id == 0 || $this->getData($id) == null)
        {
            throw new Exception(JText::_('COM_TODOLIST_ITEM_DOESNT_EXIST'), 404);
        }

        $authorised = $user->authorise('core.edit', 'com_todolist.item.' . $id);

        if ($authorised !== true) {
            $authorised = $user->authorise('core.edit.own', 'com_todolist.item.' . $id);
        }

        if ($authorised !== true)
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $table = $this->getTable('Item', 'TodolistTable');
        $table->load($id);
        $table->status = $status;

        return $table->store();
    }

    public function delete($id)
    {
        $user = JFactory::getUser();

        if ($id == 0 || $this->getData($id) == null)
        {
            throw new Exception(JText::_('COM_TODOLIST_ITEM_DOESNT_EXIST'), 404);
        }

        if ($user->authorise('core.delete', 'com_todolist.item.' . $id) !== true)
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $table = $this->getTable('Item', 'TodolistTable');

        if ($table->delete($id) !== true)
        {
            throw new Exception(JText::_('JERROR_FAILED'), 501);
        }

        return $id;
    }

    
}
