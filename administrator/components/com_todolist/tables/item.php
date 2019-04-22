<?php
defined('_JEXEC') or die;

/**
 * To-do list item table class
 *
 * @since  2.5
 */
class TodolistTableItem extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  Database object
     *
     * @since  2.5
     */
    public function __construct(&$db)
    {
        parent::__construct('#__todolist_items', 'id', $db);
    }

    /**
     * Overloaded store method for the items table.
     *
     * @param   boolean  $updateNulls  Toggle whether null values should be updated.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   2.5
     */
    public function store($updateNulls = false)
    {
        $date = JFactory::getDate()->toSql();
        $userId = JFactory::getUser()->get('id');

        $this->modified = $date;
        $this->modified_by = $userId;

        if (empty($this->id))
        {
            // New record.
            $this->created = $date;
            $this->created_by = $userId;
        }

        // Attempt to store the data.
        return parent::store($updateNulls);
    }
}
