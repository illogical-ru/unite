<?php

namespace Controller;

use App;
use Model\User  as ModelUser;
use Model\Token as ModelToken;


class User {

    public static function signup () {

        $args   = App::args();

        $user   = new ModelUser;

        $user->email    = $args->email;
        $user->password = $args->password;

        $result = [];
        $errors = $user->save();

        if   ($errors) {
            $result['errors']  = $errors;
        }
        else {
            $result['success'] = TRUE;
        }

        App::render(['json' => $result]);
    }

    public function signin () {

        $args   = App::args();

        $result = [];
        $errors = ModelUser::valid([
            'email'    => $args->email,
            'password' => $args->password,
        ]);

        if (!$errors) {

            $user = ModelUser::find(['email' => $args->email])->one();

            if     (!$user) {
                $errors['email']    = 'not_found';
            }
            elseif (!$user->is_active) {
                $errors['email']    = 'not_active';
            }
            elseif (!$user->check_password($args->password)) {
                $errors['password'] = 'does_not_match';
            }
            else   {

                list($signed, $uuid) = App::safe()->uuid_signed();

                $token = new ModelToken;

                $token->id      = $uuid;
                $token->user_id = $user->id;

                if   ($token->save()) {
                    $result['fatal'] = 'failed_to_create_token';
                }
                else {
                    $result['token'] = $signed;
                }
            }
        }
        if ( $errors) {
            $result['errors'] = $errors;
        }

        App::render(['json' => $result]);
    }

    public function signout () {

        $token  = App::token();

        $token->is_active = FALSE;

        $result = [
            'success' => !$token->save(),
        ];

        App::render(['json' => $result]);
    }
}

?>
