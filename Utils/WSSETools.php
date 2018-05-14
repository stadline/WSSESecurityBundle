<?php

namespace Stadline\WSSESecurityBundle\Utils;
/**
 * Description of WSSETool
 *
 * @author fabien
 */
class WSSETools
{
    public static function generateWsseHeader($username, $secret)
    {
        $nonce = md5(uniqid(), true);
        $created = gmdate('Y-m-d\TH:i:s\Z');
    
        $digest = base64_encode(sha1($nonce.$created.$secret, true));
        $b64nonce = base64_encode($nonce);
    
        return sprintf(
            'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $username,
            $digest,
            $b64nonce,
            $created
        );
    }
}
