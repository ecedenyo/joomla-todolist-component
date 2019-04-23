<?php
// No direct access
defined('_JEXEC') or die;

class TodolistControllerItemForm extends JControllerForm
{
    public function edit($key = NULL, $urlVar = NULL)
    {
        $app = JFactory::getApplication();

        $editId     = $app->input->getInt('id', 0);

        // Set the user id for the user to edit in the session
        $app->setUserState('com_todolist.edit.item.id', $editId);

        $this->setRedirect(JRoute::_('index.php?option=com_todolist&view=itemform', false));
    }

    public function save($key = NULL, $urlVar = NULL)
    {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables
        $app   = JFactory::getApplication();
        $model = $this->getModel('ItemForm', 'TodolistModel');

        // Get the user data
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Get the form
        $form = $model->getForm();

        if (!$form)
        {
            throw new Exception($model->getError(), 500);
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        if ($data === false)
        {
            // Get the validation messages
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                }
                else
                {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $jform = $app->input->get('jform', array(), 'ARRAY');

            // Save the data in the session
            $app->setUserState('com_todolist.edit.item.data', $jform);

            // Redirect back to the edit screen
            $id = (int) $app->getUserState('com_todolist.edit.item.id');
            $this->setRedirect(JRoute::_('index.php?option=com_todolist&view=itemform&id=' . $id, false));
        }

        // Attempt to save the data
        $return = $model->save($data);

        // Check for errors
        if ($return === false)
        {
            // Save the data in the session
            $app->setUserState('com_todolist.edit.item.data', $data);

            // Redirect back to the edit screen
            $id = (int) $app->getUserState('com_todolist.edit.item.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_todolist&view=itemform&layout=edit&id=' . $id, false));
        }

        // Clear the profile id from the session
        $app->setUserState('com_todolist.edit.item.id', null);

        // Redirect to the list screen
        $this->setMessage(JText::_('COM_TODOLIST_ITEM_SAVED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url  = (empty($item->link) ? 'index.php?option=com_todolist&view=items' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_todolist.edit.item.data', null);
    }

    public function cancel($key = NULL)
    {
        $app = JFactory::getApplication();

        // Get the current edit id
        $editId = (int) $app->getUserState('com_todolist.edit.item.id');

        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url  = (empty($item->link) ? 'index.php?option=com_todolist&view=items' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    public function remove()
    {
        $app   = JFactory::getApplication();
        $model = $this->getModel('ItemForm', 'TodolistModel');
        $pk    = $app->input->getInt('id');

        // Attempt to save the data
        try
        {
            $return = $model->delete($pk);

            // Clear the profile id from the session.
            $app->setUserState('com_todolist.edit.item.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            $url = (empty($item->link) ? 'index.php?option=com_todolist&view=items' : $item->link);

            // Redirect to the list screen
            $this->setMessage(JText::_('COM_EXAMPLE_ITEM_DELETED_SUCCESSFULLY'));
            $this->setRedirect(JRoute::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_todolist.edit.item.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? 'error' : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_todolist&view=items');
        }
    }
}
