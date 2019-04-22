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
}