<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HelloWorlds View
 */
class Soundche_circleViewFests extends JView
{
    /**
     * HelloWorlds view display method
     * @return void
     */

    protected $items;
    protected $pagination;
    protected $state;


   public function display($tpl = null)
    {
        // Get data from the model and Assign data to the view

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        $this->setDocument();

        // Set the submenu
        Soundche_circleHelper::addSubmenu('messages');
    }


        // setting the toolbar

    protected function addToolBar () {

        JToolBarHelper::addNewX('fest.add');
        JToolBarHelper::editList('fest.edit');
        JToolBarHelper::divider();
        JToolBarHelper::title(JText::_('COM_SOUNDCHE_CIRCLE_ABOUT_TITLE'));
        JToolBarHelper::publish('fests.publish'); // Will call the task/function "publish" in your controller
        JToolBarHelper::unpublish('fests.unpublish');
        JToolBarHelper::deleteList('','fests.delete');

    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_SOUNDCHE_CIRCLE_ADMINISTRATION'));
    }
}