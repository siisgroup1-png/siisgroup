<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/bdFonctions.class.php';

class BaseModel extends bdFonctions {

    public function __construct() {
        parent::__construct();
    }
}