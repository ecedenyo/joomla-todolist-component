<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * 
 */
class TodolistController extends JControllerLegacy
{
    
    public function display($cachable = false, $urlparams = false)
    {
        // We do the same we did with component admin. controller
        $app    = JFactory::getApplication();
        $view   = $app->input->getCmd('view', 'items');
        $app->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
}
