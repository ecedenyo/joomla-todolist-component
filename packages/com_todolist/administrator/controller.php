<?php 
defined('_JEXEC') or die;

/**
 * 
 */
class TodolistController extends JControllerLegacy
{
    
    public function display($cachable = false, $urlparams = false)
    {
        $view = JFactory::getApplication()->input->getCmd('view', 'items');
        JFactory::getApplication()->input->set('view', $view);

        return parent::display($cachable, $urlparams);
    }
}