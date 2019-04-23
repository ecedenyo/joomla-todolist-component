<?php
// No direct access
defined('_JEXEC') or die;

class TodolistControllerItem extends JControllerLegacy
{
    public function edit()
    {
        $app = JFactory::getApplication();

        $editId     = $app->input->getInt('id', 0);

        // Set the user id for the user to edit in the session
        $app->setUserState('com_todolist.edit.item.id', $editId);

        // Redirect to the edit screen
        $this->setRedirect(JRoute::_('index.php?option=com_todolist&view=itemform', false));
    }

    public function complete()
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Checking if the user can remove object
        $user = JFactory::getUser();

        if ($user->authorise('core.edit', 'com_todolist'))
        {
            $model = $this->getModel('Item', 'TodolistModel');

            // Get the user data
            $id     = $app->input->getInt('id');
            $status = $app->input->getInt('status');

            // Attempt to save the data
            $return = $model->complete($id, $status);

            // Check for errors
            if ($return === false)
            {
                $this->setMessage(JText::sprintf('Save failed: %s', $model->getError()), 'warning');
            }

            // Clear the profile id from the session
            $app->setUserState('com_todolist.edit.item.id', null);

            // Flush the data from the session
            $app->setUserState('com_todolist.edit.item.data', null);

            // Redirect to the list screen
            $this->setMessage(JText::_('COM_TODOLIST_ITEM_SAVED_SUCCESSFULLY'));
            $menu = JFactory::getApplication()->getMenu();
            $item = $menu->getActive();

            if (!$item)
            {
                // If there isn't any menu item active, redirect to list view
                $this->setRedirect(JRoute::_('index.php?option=com_todolist&view=items', false));
            }
            else
            {
                $this->setRedirect(JRoute::_($item->link . $menuitemid, false));
            }
        }
        else
        {
            throw new Exception(500);
        }
    }

    public function remove()
    {
        // Initialise variables
        $app = JFactory::getApplication();

        // Checking if the user can remove object
        $user = JFactory::getUser();

        if ($user->authorise('core.delete', 'com_todolist'))
        {
            $model = $this->getModel('Item', 'TodolistModel');

            // Get the user data
            $id = $app->input->getInt('id', 0);

            // Attempt to save the data
            $return = $model->delete($id);

            // Check for errors
            if ($return === false)
            {
                $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            }
            else
            {
                // Clear the profile id from the session
                $app->setUserState('com_todolist.edit.item.id', null);

                // Flush the data from the session
                $app->setUserState('com_todolist.edit.item.data', null);

                $this->setMessage(JText::_('COM_TODOLIST_ITEM_DELETED_SUCCESSFULLY'));
            }

            // Redirect to the list screen
            $menu = JFactory::getApplication()->getMenu();
            $item = $menu->getActive();
            $this->setRedirect(JRoute::_($item->link, false));
        }
        else
        {
            throw new Exception(500);
        }
    }
}
