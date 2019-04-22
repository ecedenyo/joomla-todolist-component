<?php 
defined('_JEXEC') or die;

/**
 * 
 */
class TodolistHelpersTodolist
{
    
    public static function addSubmenu($vName = '')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_TODOLIST_TITLE_ITEMS'),
            'index.php?option=com_todolist&view=items',
            $vName == 'items'
        );

        JHtmlSidebar::addEntry(
            JText::_('JCATEGORIES') . ' (' . JText::_('COM_TODOLIST_TITLE_ITEMS') . ')',
            "index.php?option=com_categories&extension=com_todolist",
            $vName == 'categories'
        );
    }

    public static function getActions()
    {
        $user   = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_todolist';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action)
        {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
}