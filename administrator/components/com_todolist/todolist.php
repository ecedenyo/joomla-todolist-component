<?php 
/**
 * To-do list administrator entry point
 */

$params = JComponentHelper::getParams('com_todolist');

$message = $params->get('message', 'Let\'s do this!');

echo $message;
