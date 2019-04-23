<?php 
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * 
 */
class TodolistControllerItem extends JControllerForm
{

    public function __construct()
    {
        $this->view_list = 'items';
        parent::__construct();
    }
}