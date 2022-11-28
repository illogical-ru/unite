<?php

namespace Controller;

use App;
use Model\Upload as ModelUpload;


class Avatar {

    public static function post () {

        $user   = App::user();

        if   (!$user) {
            return 401;
        }

        $avatar = $user->avatar();

        $upload = new ModelUpload;

        $upload->type    = 'avatar';
        $upload->user_id = $user->id;

        $result = [];
        $errors = [];

        $error  = $upload->from_files('avatar');

        if     ($error) {
            $errors['avatar']  = $error;
        }
        elseif ($upload->save()) {
            $errors['avatar']  = 'internal';
        }

        if     ($errors) {
            $result['errors']  = $errors;
        }
        else   {

            $result['success'] = TRUE;
            $result['avatar']  = $upload->url();

            if ($avatar) {
                $avatar->is_active = FALSE;
                $avatar->save();
            }
        }

        App::render(['json' => $result]);
    }

    public static function delete () {

        $user   = App::user();

        if (!$user) {
            return 401;
        }

        $avatar = $user->avatar();

        $result = [];

        if ($avatar) {
            $avatar->is_active =  FALSE;
            $result['success'] = !$avatar->save();
        }

        App::render(['json' => $result]);
    }
}

?>
