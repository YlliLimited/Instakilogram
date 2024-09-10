<?php

require "../vendor/autoload.php";
require "../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('notifications', function ($table) {
  $table->id();
  $table->integer("user_id");
  $table->text("content");
  $table->timestamps();
});