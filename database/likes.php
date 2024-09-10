<?php
require "../vendor/autoload.php";
require "../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('likes', function ($table) {
  $table->id();
  $table->integer("post_id");
  $table->integer("user_id");
  $table->timestamps();
});