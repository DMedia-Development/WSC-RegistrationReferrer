<?php

namespace wcf\system\event\listener;

use wcf\system\WCF;
use wcf\data\user\User;
use wcf\data\user\UserEditor;

/**
 * Sets the user profile filed for the registration referrer
 *
 * @author Moritz Dahlke (DMedia)
 * @copyright 2021 DMedia
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Event\Listener
 */
class RegistrationReferrerRegisterFormEventListener implements IParameterizedEventListener
{

    /**
     * session variable name for the registration referrer
     * @var string
     */
    protected $session_variable = "original_http_referrer";

    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        $this->$eventName($eventObj);
    }

    /**
     * @inheritDoc
     */
    protected function saved() {

        if (isset(WCF::getSession()->getUser()->userID)) {

            if (!is_null(WCF::getSession()->getVar($this->session_variable))) {

                $userEditor = new UserEditor(WCF::getUser());
                $userEditor->updateUserOptions(array(
                    User::getUserOptionID("registrationReferrer") => WCF::getSession()->getVar($this->session_variable)
                ));

                WCF::getSession()->unregister($this->session_variable);

            } else {
                $userEditor = new UserEditor(WCF::getUser());
                $userEditor->updateUserOptions(array(
                    User::getUserOptionID("registrationReferrer") => "/"
                ));
            }

        }

    }

}