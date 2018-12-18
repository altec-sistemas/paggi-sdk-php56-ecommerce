<?php
/**
 * This file verify if the token is valid
 *
 * PHP version 5.6, 7.0, 7.1, 7.2
 *
 * @category Token_File
 * @package  Paggi
 * @author   Paggi Integracoes <email@email.com>
 * @license  GNU GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link     http://developers.paggi.com
 */

 namespace Paggi\SDK;

 use Paggi\SDK\Interfaces;
 use Lcobucci\JWT\ValidationData;
 use Lcobucci\JWT\Parser;

 /**
  * This file verify if the token is valid
  *
  * @category Token_Class
  * @package  Paggi
  * @author   Paggi Integracoes <ti-integracoes@paggi.com>
  * @license  GNU GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
  * @link     http://developers.paggi.com
  */
class TokenValidation
{
    /**
     * Function responsible for the token's validation
     *
     * @param string $initialToken Authentication Token
     *
     * @return boolean
     */
    public static function isValidToken($initialToken)
    {
        $token = (new Parser())->parse((string) $initialToken);
        if (!$token->hasClaim('permissions')) {
            return false;
        }
        $partnerId = $token->getClaim('permissions')[0]->partner_id;
        return (
            isset($partnerId)
            && !is_null($partnerId)
            && !empty($partnerId)
        ) ? true : false;
    }

    /**
     * Function responsible for the token's expiration
     *
     * @param string $initialToken Authentication Token
     *
     * @return boolean
     */
    public static function isExpiredToken($initialToken)
    {
        $time = self::expirateHelper($initialToken);
        return (time() > $time) ? true : false;
    }

    /**
     * Function who verify if token's expiration date is within a month from today
     *
     * @param string $initialToken Authentication Token
     *
     * @return boolean
     */
    public static function isExpiringToken($initialToken)
    {
        $time = self::expirateHelper($initialToken);
        return (time() > $time - 2592000) ? true : false;
    }

    /**
     * Function who will help the other function to not repeat code
     *
     * @param string $initialToken Authentication Token
     *
     * @return void
     */
    public static function expirateHelper($initialToken)
    {
        $token = (new Parser())->parse((string) $initialToken);
        return $token->getClaim('exp');
    }
}
