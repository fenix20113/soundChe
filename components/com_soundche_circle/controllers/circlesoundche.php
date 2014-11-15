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

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Circlesoundche controller class.
 */
class Soundche_circleControllerCirclesoundche extends Soundche_circleController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_soundche_circle.edit.circlesoundche.id');
        $editId = JFactory::getApplication()->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_soundche_circle.edit.circlesoundche.id', $editId);

        // Get the model.
        $model = $this->getModel('Circlesoundche', 'Soundche_circleModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId && $previousId !== $editId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_soundche_circle&view=circlesoundcheform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Circlesoundche', 'Soundche_circleModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_soundche_circle.edit.circlesoundche.data', JRequest::getVar('jform'), array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_soundche_circle.edit.circlesoundche.id');
            $this->setRedirect(JRoute::_('index.php?option=com_soundche_circle&view=circlesoundche&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_soundche_circle.edit.circlesoundche.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_soundche_circle.edit.circlesoundche.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_soundche_circle&view=circlesoundche&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_soundche_circle.edit.circlesoundche.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_SOUNDCHE_CIRCLE_ITEM_SAVED_SUCCESSFULLY'));
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));

        // Flush the data from the session.
        $app->setUserState('com_soundche_circle.edit.circlesoundche.data', null);
    }

    function cancel() {
        $app = JFactory::getApplication();
        $previousId = (int) $app->getUserState('com_raector_crm.edit.project.id');
        if ($previousId) {
            // Get the model.
            $model = $this->getModel('Project', 'Raector_crmModel');
            $model->checkin($previousId);
        }
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));
    }

    public function remove() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('Circlesoundche', 'Soundche_circleModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_soundche_circle.edit.circlesoundche.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_soundche_circle.edit.circlesoundche.id');
            $this->setRedirect(JRoute::_('index.php?option=com_soundche_circle&view=circlesoundche&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_soundche_circle.edit.circlesoundche.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_soundche_circle.edit.circlesoundche.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_soundche_circle&view=circlesoundche&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_soundche_circle.edit.circlesoundche.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_SOUNDCHE_CIRCLE_ITEM_DELETED_SUCCESSFULLY'));
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));

        // Flush the data from the session.
        $app->setUserState('com_soundche_circle.edit.circlesoundche.data', null);
    }

}
