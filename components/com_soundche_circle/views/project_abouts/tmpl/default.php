<?php
/**
 * @version     1.0.0
 * @package     com_soundche_circle
 * @copyright   SoundЧe © 2014. Все права защищены.
 * @license     GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 * @author      Yuri Palii <ypalii2012@gmail.com> - http://
 */
// no direct access
defined('_JEXEC') or die;?>

<?php $show = false; ?>

<div class="sidebar">
    <ul>
        <li>
            <a class="now" href="<?php echo JRoute::_('index.php?option=com_soundche_circle&view=project_abouts&id=' . (int)$this->item->id); ?>"><?php echo JText::_("COM_SOUNDCHE_CIRCLE_PROJECT_ABOUT"); ?></a>
        </li>

        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_soundche_circle&view=team&id=' . (int)$this->item->id.'&layout=default_project'); ?>"><?php echo JText::_("COM_SOUNDCHE_CIRCLE_TEAM"); ?></a>

        </li>

        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_soundche_circle&view=partners&id=' . (int)$this->item->id.'&layout=default_project'); ?>"><?php echo JText::_("COM_SOUNDCHE_CIRCLE_PARTNERS"); ?></a>
        </li>

        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_soundche_circle&view=quests&id=' . (int)$this->item->id.'&layout=default_project'); ?>"><?php echo JText::_("COM_SOUNDCHE_CIRCLE_GUESTS"); ?></a>
        </li>

    </ul>


</div>

<div class="content">

    <ul class="c_marg">


        <?php foreach ($this->items as $item): ?>

            <?php if ($item->published == 1 || ($item->published == 0 && JFactory::getUser()->authorise('core.edit.own', ' com_soundche_circle.partners.' . $item->id))):
                $show = true;?>



                <li>
<a class="event">  <?php echo JHtml::_('image',$item->image,JText::_('COM_SOUNDCHE_CIRCLE_IMAGE_ALT="Зображення'));?>
</a>
                    <div class="inf">
                        <h2><?php echo $item->title;?></h2>

                      <p> <?php echo $item->description;?></p>


                    </div>


                </li>

            <?php endif; ?>

        <?php endforeach; ?>
    </ul>
</div>

<!---->
<!---->
<!--<pre>--><?php
//
//    print_r($this->items)
//
?><!--</pre>-->
<!---->
<?php
if (!$show):
    echo JText::_('COM_SOUNDCHE_CIRCLE_NO_ITEMS');
endif;

if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php //echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

