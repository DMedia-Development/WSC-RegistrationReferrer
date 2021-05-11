<?php

namespace wcf\system\option\user;

use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\util\StringUtil;

/**
 * User option output implementation for the output of the registration referrer.
 *
 * @author Moritz Dahlke (DMedia)
 * @copyright 2021 DMedia
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Option\User
 */
class RegistrationReferrerUserOptionOutput implements IUserOptionOutput
{

    /**
     * @inheritDoc
     */
    public function getOutput(User $user, UserOption $option, $value)
    {
        $output = "";

        if (!empty($value)) {

            if (filter_var($value, FILTER_VALIDATE_URL)) {
                $value = self::getURL($value);
                $output = StringUtil::getAnchorTag($value, $value, true, true);
            } else  {
                $output = $value;
            }

        }

        return $output;
    }

    private static function getURL($url)
    {
        if (!\preg_match('~^https?://~i', $url)) {
            $url = 'http://' . $url;
        }

        return $url;
    }

}