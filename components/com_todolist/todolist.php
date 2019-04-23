<?php 
/**
 * To-do list frontend entry point
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

JLoader::registerPrefix('Todolist', JPATH_COMPONENT); // register all classes beggining w/ Todolist into the Component Path
JLoader::register('TodolistController', JPATH_COMPONENT . '/controller.php');

$controller = JControllerLegacy::getInstance('Todolist'); // know how to load the propper controller based on the query params. in the url

$controller->execute(JFactory::getApplication()->input->get('task'));

$controller->redirect();
