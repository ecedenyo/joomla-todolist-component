<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * 
 */
class TodolistControllerItems extends JControllerAdmin
{
    
    public function duplicate()
    {
        Jsession::checkToken() or jexist(JText::_('JINVALID_TOKEN'));

        $pks = $this->input->post->get('cid', array(), 'array');

        try
        {
            if (empty($pks))
            {
                throw new Exception(JText::_('COM_TODOLIST_NO_ELEMENT_SELECTED'));
            }

            ArrayHelper::toInteger($pks);
            $model = $this->getModel();
            $model->duplicate($pks);
            $this->setMessage(Jtext::_('COM_TODOLIST_ITEMS_SUCCESS_DUPLICATED'));
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage('On file items.php: ' . $e->getMessage(), 'warning');
        }

        $this->setRedirect('index.php?option=com_todolist&view=items');
    }

    public function getModel($name = 'item', $prefix = 'TodolistModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }

    public function saveOrderAjax()
    {
        // Get the input
        $input  = JFactory::getApplication()->input;
        $pks    = $input->post->get('cid', array(), 'array');
        $order  = $input->post->get('order', array(), 'array');

        // Sanitize the input
        ArrayHelper::toInteger($pks);
        ArrayHelper::toInteger($order);

        // Get the model
        $model  = $this->getModel();

        // Save the ordering
        $return = $model->saveorder($pks, $order);

        if ($return)
        {
            echo "1";
        }

        // Close the application
        JFactory::getApplication()->close();
    }
}