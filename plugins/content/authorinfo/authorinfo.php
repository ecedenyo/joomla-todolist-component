<?php 
defined('_JEXEC') or die;

class PlgContentAuthorinfo extends JPlugin 
{
    public function onContentPrepare($context, &$row, $params, $page = 0) 
    {
        $row->text .= 'Hello world';

        return true;
    }
}