<?php
namespace wcf\system\event\listener;
use wcf\system\WCF;

class RegistrationReferrerTemplateEngineEventListener implements IParameterizedEventListener {
    protected $session_variable = "original_http_referrer";

    public function execute($eventObj, $className, $eventName, array &$parameters) {
        $this->$eventName($eventObj);
    }

    protected function beforeDisplay() {

        /*
        * 'HTTP_REFERER'
        * The address of the page (if any) which referred the user agent to the current page.
        * This is set by the user agent. Not all user agents will set this, and some provide the ability to modify HTTP_REFERER as a feature.
        * In short, it cannot really be trusted. 
        */

        if (!WCF::getUser()->userID) {

            if (is_null(WCF::getSession()->getVar($this->session_variable))) {
                if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])) {
                    WCF::getSession()->register($this->session_variable, $_SERVER["HTTP_REFERER"]);
                } else {
                    $protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https://" : "http://";
                    $current_url = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                    WCF::getSession()->register($this->session_variable, $current_url);
                }
            }

        } else {

            if (!is_null(WCF::getSession()->getVar($this->session_variable))) {
                WCF::getSession()->unregister($this->session_variable);
            }

        }

    }

}