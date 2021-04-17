<?php

namespace wcf\system\event\listener;
use wcf\system\WCF;
use wcf\data\user\User;
use wcf\data\user\UserEditor;

class RegistrationReferrerRegisterFormEventListener implements IParameterizedEventListener {
    protected $session_variable = "original_http_referrer";

    public function execute($eventObj, $className, $eventName, array &$parameters) {
        $this->$eventName($eventObj);
    }

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
                    User::getUserOptionID("registrationReferrer") => "https://google.com/"
                ));
            }

        }

    }

}