<?php
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_todolist.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_todolist' . $this->item->id))
{
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="todolist-item">

    <h1>
        <?php echo $this->item->title; ?>
    </h1>

    <div class="todolist-item-description">
        <?php echo $this->item->description; ?>
    </div>

    <ul>
        <li>
            <?php echo JText::_('COM_TODOLIST_FORM_LBL_ITEM_CREATED_BY'); ?>: <?php echo $this->item->created_by_name; ?>
        </li>
            
        <li>
            <?php echo JText::_('COM_TODOLIST_FORM_LBL_ITEM_MODIFIED_BY'); ?>: <?php echo $this->item->modified_by_name; ?>
        </li>

        <li>
            <?php echo JText::_('COM_TODOLIST_FORM_LBL_ITEM_COMPLETED'); ?>: <i class="icon-<?php echo ($this->item->status) ? 'publish' : 'unpublish'; ?>"></i>
        </li>

        <li>
            <?php echo JText::_('COM_TODOLIST_FORM_LBL_ITEM_CATID'); ?>: <?php echo $this->item->cat_title; ?>
        </li>
    </ul>

</div>

<?php if($canEdit): ?>
    <a class="btn" href="<?php echo JRoute::_('index.php?option=com_todolist&task=item.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_TODOLIST_EDIT_ITEM"); ?></a>
<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_todolist.item.'.$this->item->id)) : ?>
    <a class="btn" href="<?php echo JRoute::_('index.php?option=com_todolist&task=item.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_TODOLIST_DELETE_ITEM"); ?></a>
<?php endif; ?>
