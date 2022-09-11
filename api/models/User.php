<?php

namespace Model;

use App;
use Lava\Model\SQL;
use Model\Token;


class User extends SQL {

    protected static
        $table   = 'users',
        $columns = [
            'id'        => [
                'not_null' => FALSE,
            ],
            'email'     => [
                'not_null' => TRUE,
                'unique'   => TRUE,
                'valid'    => ['string:1:48', 'email'],
            ],
            'password'  => [
                'not_null' => TRUE,
                'valid'    => 'string:7',
            ],
            'salt'      => [
                'not_null' => TRUE,
            ],
            'created'   => [
                'not_null' => TRUE,
            ],
            'is_active' => [
                'not_null' => TRUE,
                'default'  => TRUE,
            ],
        ];


    // --- default ---------------------------------------------------------- //

    public static function column_default_id () {
        return App::safe()->uuid();
    }
    public static function column_default_created () {
        return date('Y-m-d H:i:s');
    }
    public static function column_default_salt () {
        return md5(uniqid());
    }

    // --- export ----------------------------------------------------------- //

    public static function export ($data) {

        if (isset($data['password']) && isset($data['salt'])) {
            $data['password'] = md5($data['password'] . $data['salt']);
        }

        return $data;
    }

    // --- rels ------------------------------------------------------------- //

    public function tokens () {
        return $this->has_many(Token::classname());
    }

    // --- func ------------------------------------------------------------- //

    public function check_password ($password) {
        return $this->password === md5($password . $this->salt);
    }
}

?>
