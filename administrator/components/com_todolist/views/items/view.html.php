<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * 
 */
class TodolistViewItems extends JViewLegacy
{
    
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        if(count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("n", $errors));
        }

        TodolistHelpersTodolist::addSubmenu('items');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $state = $this->get('State');
        $canDo = TodolistHelpersTodolist::getActions();

        JToolBarHelper::title(JText::_('COM_TODOLIST_TITLE_ITEMS'), 'items.png');

        if ($canDo->get('core.create'))
        {
            JToolBarHelper::addNew('item.add', 'JTOOLBAR_NEW');
            JToolbarHelper::custom('items.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        }

        if (isset($this->items[0]))
        {
            if($canDo->get('core.edit'))
            {
                JToolBarHelper::editList('item.edit', 'JTOOLBAR_EDIT');
            }

            if($canDo->get('core.edit.state'))
            {
                JToolBarHelper::divider();
                JToolBarHelper::custom('items.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('items.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);

                JToolBarHelper::divider();
                JToolBarHelper::archiveList('items.archive', 'JTOOLBAR_ARCHIVE');
            }

        }

        if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
        {
            JToolBarHelper::deleteList('', 'items.delete', 'JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolBarHelper::trash('items.trash', 'JTOOLBAR_TRASH');
            JToolBarHelper::divider();
        }

        if ($canDo->get('core.admin'))
        {
            JToolBarHelper::preferences('com_todolist');
        }

        JHtmlSidebar::setAction('index.php?option=com_todolist&view=items');

        JHtmlSidebar::addFilter(

            JText::_('JOPTION_SELECT_PUBLISHED'),

            'filter_published',

            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

        );

        $select_label = JText::_('COM_TODOLIST_STATUS_FILTER');
        $options = array();
        $options[0] = new stdClass();
        $options[0]->value = "0";
        $options[0]->text = COM_TODOLIST_STATUS_INCOMPLETE;
        $options[1] = new stdClass();
        $options[1]->value = "1";
        $options[1]->text = COM_TODOLIST_STATUS_COMPLETE;
        JHtmlSidebar::addFilter(
            $select_label,
            'filter_status',
            JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.status'), true)
        );

        JHtmlSidebar::addFilter(
            JText::_("JOPTION_SELECT_CATEGORY"),
            'filter_catid',
            JHtml::_('select.options', JHtml::_('category.options', 'com_todolist'), "value", "text", $this->state->get('filter.catid'))

        );
    }

    protected function getSortFields()
    {
        return array(
            'a.`id`' => JText::_('JGRID_HEADING_ID'),
            'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
            'a.`state`' => JText::_('JSTATUS'),
            'a.`status`' => JText::_('COM_TODOLIST_ITEMS_STATUS'),
            'a.`title`' => JText::_('COM_TODOLIST_ITEMS_TITLE'),
            'a.`catid`' => JText::_('COM_TODOLIST_ITEMS_CATID'),
        );
    }
}