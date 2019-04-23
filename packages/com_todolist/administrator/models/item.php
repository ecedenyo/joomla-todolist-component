<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class TodolistModelItem extends JModelAdmin
{
    protected $text_prefix = 'COM_TODOLIST';

    protected $item = null;

    // Retrieve the table created and defined in /tables/item.php
    public function getTable($type = 'Item', $prefix = 'TodolistTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    // Retrieve the form defined in forms/item.xml
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_todolist.item', 'item',
            array('control' => 'jform',
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
        // If we are editing a form ...
        $data = JFactory::getApplication()->getUserState('com_todolist.edit.item.data', array());

        // If we are creating a new item ...
        if (empty($data))
        {
            if ($this->item === null)
            {
                $this->item = $this->getItem();
            }

            $data = $this->item;
        }

        return $data;
    }

    public function duplicate(&$pks)
    {
        $user = JFactory::getUser();

        if(!$user->authorise('core.create', 'com_todolist'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $dispatcher = JEventDispatcher::getInstance();
        $context    = $this->option . '.' . $this->name;

        JPluginHelper::importPlugin($this->events_map['save']);

        $table = $this->getTable();

        foreach ($pks as $pk)
        {
            if ($table->load($pk, true))
            {
                $table->id = 0;

                if (!$table->check())
                {
                    throw new Exception($table->getError());
                }
                
                $result = $dispatcher->trigger($this->event_before_save, array($context, &$table, true));

                if (in_array(false, $result, true) || !$table->store())
                {
                    throw new Exception($table->getError());
                }

                // Trigger the after save event.
                $dispatcher->trigger($this->event_after_save, array($context, &$table, true));
            }
            else
            {
                throw new Exception($table->getError());
            }
        }

        // Clean cache
        $this->cleanCache();

        return true;
    }

    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');

        if (empty($table->id))
        {
            // Set ordering to the last item if not set
            if (@$table->ordering === '')
            {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__todolist_items');
                $max             = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }
}
