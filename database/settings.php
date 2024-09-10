<?php
require "../vendor/autoload.php";
require "../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('settings', function ($table) {
  $table->id();
  $table->integer("user_id");
  $table->string("prefered_theme", 5)->default("light");
});