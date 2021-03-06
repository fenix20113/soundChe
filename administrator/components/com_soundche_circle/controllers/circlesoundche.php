<?php
/**
 * @version     1.0.0
 * @package     com_soundche_circle
 * @copyright   SoundЧe © 2014. Все права защищены.
 * @license     GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 * @author      Yuri Palii <ypalii2012@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Circlesoundche controller class.
 */
class Soundche_circleControllerCirclesoundche extends JControllerForm
{

    function __construct() {
        $this->view_list = 'circlesoundches';
        parent::__construct();
    }

}