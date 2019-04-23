<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

use Joomla\Utilities\ArrayHelper;

class TodolistModelItemForm extends JModelForm
{
    private $item = null;

    protected function populateState()
    {
        $app = JFactory::getApplication('com_todolist');

        $id = JFactory::getApplication()->getUserState('com_todolist.edit.item.id');

        if (!$id) {
            $id = JFactory::getApplication()->input->get('id');
        }
        JFactory::getApplication()->setUserState('com_todolist.edit.item.id', $id);

        $this->setState('item.id', $id);

        // Load the parameters
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
        if ($this->item === null)
        {
            $this->item = false;

            if (empty($id))
            {
                $id = $this->getState('item.id');
            }

            // Get a level row instance
            $table = $this->getTable('Item', 'TodolistTable');

            // Attempt to load the row.
            if ($table !== false && $table->load($id))
            {
                $user = JFactory::getUser();
                $id   = $table->id;
                
                if ($id)
                {
                    $canEdit = $user->authorise('core.edit', 'com_todolist.item.' . $id) || $user->authorise('core.create', 'com_todolist.item.' . $id);
                }
                else
                {
                    $canEdit = $user->authorise('core.edit', 'com_todolist') || $user->authorise('core.create', 'com_todolist');
                }

                if (!$canEdit && $user->authorise('core.edit.own', 'com_todolist.item.' . $id))
                {
                    $canEdit = $user->id == $table->created_by;
                }

                if (!$canEdit)
                {
                    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 500);
                }

                // Convert the JTable to a clean JObject.
                $properties  = $table->getProperties(1);
                $this->item = ArrayHelper::toObject($properties, 'JObject');
            }
        }

        return $this->item;
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_todolist.item', 'itemform', array(
            'control'   => 'jform',
            'load_data' => $loadData
            )
        );

        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_todolist.edit.item.data', array());

        if (empty($data))
        {
            $data = $this->getData();
        }

        return $data;
    }

    public function save($data)
    {
        $id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('item.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user  = JFactory::getUser();

        if ($id)
        {
            $authorised = $user->authorise('core.edit', 'com_todolist.item.' . $id);

            if ($authorised !== true) {
                $authorised = $user->authorise('core.edit.own', 'com_todolist.item.' . $id);
            }
        }
        else
        {
            $authorised = $user->authorise('core.create', 'com_todolist');
        }

        if ($authorised !== true)
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $table = $this->getTable('Item', 'TodolistTable');

        if ($table->save($data) === true)
        {
            return $table->id;
        }
        else
        {
            return false;
        }
    }

    public function delete($pk)
    {
        $user = JFactory::getUser();

        if (empty($pk))
        {
            $pk = (int) $this->getState('item.id');
        }

        if ($pk == 0 || $this->getData($pk) == null)
        {
            throw new Exception(JText::_('COM_TODOLIST_ITEM_DOESNT_EXIST'), 404);
        }

        if ($user->authorise('core.delete', 'com_todolist.item.' . $id) !== true)
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $table = $this->getTable('Item', 'TodolistTable');

        if ($table->delete($pk) !== true)
        {
            throw new Exception(JText::_('JERROR_FAILED'), 501);
        }

        return $pk;
    }
    
}
