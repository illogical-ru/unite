<?php

namespace Model;

use App;
use Lava\Model\SQL;
use Model\User;


class Upload extends SQL {

    protected static
        $table   = 'uploads',
        $columns = [
            'id'        => [
                'not_null' => FALSE,
            ],
            'type'      => [
                'not_null' => TRUE,
                'valid'    => [[
                    'avatar',
                ]],
            ],
            'name'      => [
                'not_null' => TRUE,
            ],
            'size'      => [
                'not_null' => TRUE,
                'valid'    => ['integer:unsigned'],
            ],
            'user_id'   => [
            ],
            'created'   => [
                'not_null' => TRUE,
            ],
            'is_active' => [
                'not_null' => TRUE,
                'default'  => TRUE,
            ],
        ];

    // --- const ------------------------------------------------------------ //

    const IMAGE_EXT = ['gif', 'jpg', 'png'];

    // --- default ---------------------------------------------------------- //

    public static function column_default_id () {
        return App::safe()->uuid();
    }
    public static function column_default_created () {
        return date('Y-m-d H:i:s');
    }

    // --- rels ------------------------------------------------------------- //

    public function user () {
        return $this->has_one(User::classname());
    }

    // --- func ------------------------------------------------------------- //

    public function home () {
        return self::$table;
    }

    public function path () {

        $path   = sscanf($this->id, '%3s %3s');
        $path[] =        $this->id;

        return $path;
    }

    public function url () {

        $path   = $this->path();
        $path[] = $this->name;
        array_unshift($path, $this->home());

        return App::url(App::pub($path));
    }

    public function from_files ($key, $index = 0) {

        if (!isset($_FILES[$key])) {
            return 'null';
        }

        $file = [];

        foreach ($_FILES[$key] as $fkey => $vals) {

            $vals = (array)$vals;

            if (!isset($vals[$index])) {
                return 'null';
            }

            $file[$fkey] = $vals[$index];
        }

        if ( $file['error']) {
            return 'internal';
        }
        if (!$file['size']) {
            return 'null';
        }

        $info = pathinfo         ($file['name']);
        $ext  = strtolower       ($info['extension']);
        $mime = mime_content_type($file['tmp_name']);

        if (    in_array($this->type, ['avatar'])
            && !in_array($ext, self::IMAGE_EXT)
            ||  strpos  ($mime, 'image/') !== 0
        )
        {
            return 'invalid';
        }

        $this->name = $file['name'];
        $this->size = $file['size'];

        $path = $this->path();

        for ($i = 0; $i++ < count($path);) {

            $node = array_slice($path, 0, $i);
            array_unshift($node, $this->home());
            $node = App::home($node);

            if (!file_exists($node)) {
                mkdir($node);
            }
        }

        if (!move_uploaded_file(
            $file['tmp_name'],
            $node . '/' . $file['name']
        ))
        {
            return 'internal';
        }
    }
}

?>
