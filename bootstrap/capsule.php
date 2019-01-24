<?php

use Illuminate\Database\Capsule\Manager as DB;

$db = new DB;

$db->addConnection(require_once __DIR__ . "/../config/database.php");

// Make this Capsule instance available globally via static methods... (optional)
$db->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$db->bootEloquent();