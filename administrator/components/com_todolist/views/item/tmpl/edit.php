<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Loading JS libs. / behaviors
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {
        
    });

    Joomla.submitbutton = function (task) {
        if (task == 'item.cancel') {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        else {
            
            if (task != 'item.cancel' && document.formvalidator.isValid(document.id('item-form'))) {
                
                Joomla.submitform(task, document.getElementById('item-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form
    action="<?php echo JRoute::_('index.php?option=com_todolist&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">

    <?php echo $this->form->renderField('title'); ?>

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_TODOLIST_TITLE_ITEM', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
                    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

                    <?php echo $this->form->renderField('status'); ?>
                    <?php echo $this->form->renderField('catid'); ?>
                    <?php echo $this->form->renderField('description'); ?>

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_TODOLIST_TITLE_PUBLISHING', true)); ?>
            <?php echo $this->form->renderField('state'); ?>
            <?php echo $this->form->renderField('created_by'); ?>
            <?php echo $this->form->renderField('modified_by'); ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
