<?php

use Model\User  as ModelUser;
use Model\Token as ModelToken;


class App extends Lava\App {

    private static
        $token,
        $user;


    // --- user ------------------------------------------------------------- //

    public static function token () {

        if (is_null(self::$token)) {

            list($type, $token) = explode(
                ' ', self::env()->http_authorization
            );

            $id = $type == 'Bearer' && $token
                ? self::safe()->check($token)
                : NULL;

            if ( $id) {
                self::$token = ModelToken
                    ::find([
                        'id'        => $id,
                        'is_active' => TRUE,
                    ])
                    ->one();
            }
            if (!self::$token) {
                self::$token = FALSE;
            }
        }

        return self::$token;
    }
    public static function user () {
        
        if (is_null(self::$user)) {

            $token = self::token();

            if ( $token) {
                self::$user = ModelUser
                    ::find([
                        'id'        => $token->user_id,
                        'is_active' => TRUE,
                    ])
                    ->one();
            }
            if (!self::$user) {
                self::$user = FALSE;
            }
        }

        return self::$user;
    }
}

?>
