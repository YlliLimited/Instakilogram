<?php
require "../vendor/autoload.php";
require "../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('posts', function ($table) {
  $table->id();
  $table->integer("user_id");
  $table->text("caption", 150);
  $table->string("photo");
  $table->timestamps();
});