<?php
// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_todolist', JPATH_SITE);

$user    = JFactory::getUser();
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_todolist.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_todolist' . $this->item->id))
{
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="todolist-item-edit front-end-edit">
    <?php if (!$canEdit) : ?>
        <h3>
            <?php throw new Exception(JText::_('COM_TODOLIST_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
        </h3>
    <?php else : ?>
        <?php if (!empty($this->item->id)): ?>
            <h1><?php echo JText::sprintf('COM_TODOLIST_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
        <?php else: ?>
            <h1><?php echo JText::_('COM_TODOLIST_ADD_ITEM_TITLE'); ?></h1>
        <?php endif; ?>

        <form id="form-item"
              action="<?php echo JRoute::_('index.php?option=com_todolist&task=itemform.save'); ?>"
              method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
            
            <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
            <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
            <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

            <?php echo $this->form->getInput('created_by'); ?>
            <?php echo $this->form->getInput('modified_by'); ?>
            <?php echo $this->form->renderField('title'); ?>
            <?php echo $this->form->renderField('description'); ?>
            <?php echo $this->form->renderField('status'); ?>
            <?php echo $this->form->renderField('catid'); ?>

            <div class="control-group">
                <div class="controls">
                    <?php if ($canEdit): ?>
                        <button type="submit" class="validate btn btn-primary">
                            <?php echo JText::_('JSUBMIT'); ?>
                        </button>
                    <?php endif; ?>
                    <a class="btn"
                       href="<?php echo JRoute::_('index.php?option=com_todolist&task=itemform.cancel'); ?>"
                       title="<?php echo JText::_('JCANCEL'); ?>">
                        <?php echo JText::_('JCANCEL'); ?>
                    </a>
                </div>
            </div>

            <input type="hidden" name="option" value="com_todolist"/>
            <input type="hidden" name="task" value="itemform.save"/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    <?php endif; ?>
</div>
