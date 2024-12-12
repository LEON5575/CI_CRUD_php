<?php

namespace App\Models;

use CodeIgniter\Model;

class Demo extends Model {
    protected $table = 'users';
    protected $allowedFields = ['id', 'name', 'email','mobile']; // Specify the fields that can be inserted

    public function __construct() {
        parent::__construct();
    }

    public function insert_data($data) {
        if ($this->insert($data)) {
            return 1;
        } else {
            return 0;
        }
    }
}

?>