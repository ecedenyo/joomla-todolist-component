<?php
// No direct access
defined('_JEXEC') or die;

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_todolist');
$canEdit    = $user->authorise('core.edit.state', 'com_todolist');
$canDelete  = $user->authorise('core.delete', 'com_todolist');
?>

<ul>
    <?php foreach ($this->items as $i => $item) : ?>
        <?php $canEdit = $user->authorise('core.edit', 'com_todolist'); ?>

        <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_todolist')): ?>
            <?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
        <?php endif; ?>

        <li>
            <?php $class = ($canEdit) ? 'active' : 'disabled'; ?>
            <?php $newstatus = 1 - $item->status; ?>
            <a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canEdit) ? JRoute::_('index.php?option=com_todolist&task=items.complete&id=' . $item->id . '&status=' . $newstatus) : '#'; ?>">
                <?php if ($item->status): ?>
                    <i class="icon-publish"></i>
                <?php else: ?>
                    <i class="icon-unpublish"></i>
                <?php endif; ?>
            </a>

            <a href="<?php echo JRoute::_('index.php?option=com_todolist&view=item&id='.(int) $item->id); ?>"><?php echo $this->escape($item->title); ?></a>

            | <?php echo $item->cat_title; ?>

            <?php if ($canEdit): ?>
                | <a href="<?php echo JRoute::_('index.php?option=com_todolist&task=itemform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
            <?php endif; ?>
            <?php if ($canDelete): ?>
                | <a href="<?php echo JRoute::_('index.php?option=com_todolist&task=items.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

<?php echo $this->pagination->getListFooter(); ?>

<?php if ($canCreate) : ?>
<a href="<?php echo JRoute::_('index.php?option=com_todolist&task=itemform.edit&id=0', false, 2); ?>" class="btn btn-success btn-small">
   <i class="icon-plus"></i> <?php echo JText::_('COM_TODOLIST_ADD_ITEM'); ?>
</a>
<?php endif; ?>

<?php if($canDelete) : ?>
<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {

        if (!confirm("<?php echo JText::_('COM_TODOLIST_DELETE_MESSAGE'); ?>")) {
            return false;
        }
    }
</script>
<?php endif; ?>
