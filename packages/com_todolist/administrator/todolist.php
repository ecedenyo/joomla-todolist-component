<?php 
/**
 * To-do list administrator entry point
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

JLoader::registerPrefix('Todolist', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Todolist');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
