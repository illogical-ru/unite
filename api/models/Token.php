<?php

namespace Model;

use App;
use Lava\Model\SQL;
use Model\User;


class Token extends SQL {

    protected static
        $table   = 'tokens',
        $columns = [
            'id'        => [
                'not_null' => TRUE,
            ],
            'user_id'   => [
                'not_null' => TRUE,
            ],
            'ip'        => [
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

    public static function column_default_ip () {
        return App::env()->remote_addr;
    }
    public static function column_default_created () {
        return date('Y-m-d H:i:s');
    }

    // --- export ----------------------------------------------------------- //

    public static function export ($data) {

        if (isset($data['ip'])) {
            $data['ip'] = ip2long($data['ip']);
        }

        return $data;
    }

    // --- rels ------------------------------------------------------------- //

    public function user () {
        return $this->has_one(User::classname());
    }
}

?>
