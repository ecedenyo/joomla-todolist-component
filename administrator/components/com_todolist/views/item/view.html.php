<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * 
 */
class TodolistViewItem extends JViewLegacy
{
    protected $state;
    protected $item;
    protected $form;

    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        if(count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("n", $errors));
        }

        $this->addToolbar();
        
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        JToolBarHelper::title(JText::_('COM_TODOLIST_TITLE_ITEM'), 'item.png');

        JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('item.save', 'JTOOLBAR_SAVE');

        JToolBarHelper::custom('item.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

        if (!$isNew)
        {
            JToolBarHelper::custom('item.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
        }

        if (empty($this->item->id))
        {
            JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CANCEL');
        }
        else
        {
            JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}