<?php
require "../vendor/autoload.php";
require "../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('follows', function ($table) {
  $table->id();
  $table->integer("follower_id");
  $table->integer("following_id");
  $table->timestamps();
});