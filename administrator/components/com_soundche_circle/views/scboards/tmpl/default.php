<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('script','system/multiselect.ls',false,true);

$user =JFactory::getUser();
$userId=$user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));




?>
<form action="<?php echo JRoute::_('index.php?option=com_soundche_circle&view=scboard'); ?>" method="post" name="adminForm" id="adminForm">

    <fieldset id="filter-bar">
        <div class="filter-search fltlftr">
            <label class="filter-search-lbl" for="filter_search" > <?php echo JText::_('JSEARCH_FILTER_LABEL')?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search'))
            ?>" title="<?php echo JText::_('COM_SOUNDCHE_CIRCLE_SEARCH_IN_TITLE')?>">
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT')?></button>

            <button onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR')?></button>


        </div>
<div class="filter-select fltrt">

    <select name="filter_published" class="inputbox" onchange="this.form.submit();">
    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
    <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'),value,'text',$this->state->get('filter.state'),true);?></select>


    <!-- filter access   -->


<!--    <select name="filter_access" class="inputbox" onchange="this.form.submit();">-->
<!--        <option value="">--><?php //echo JText::_('JOPTION_SELECT_ACCESS');?><!--</option>-->
<!--        --><?php //echo JHtml::_('select.options', JHtml::_('access.assetgroups'),value,'text',$this->state->get('filter.access'),true);?><!--</select>-->
</div>



    </fieldset>



    <table class="adminlist">
        <thead><?php echo $this->loadTemplate('head');?></thead>
        <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
        <tbody><?php echo $this->loadTemplate('body');?></tbody>
    </table>
</pre>
    <div>

        <input type="hidden" name="filter_order" value="<?php echo $listOrder?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn?>" />
        <input type="hidden" name="view" value="scboards" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>