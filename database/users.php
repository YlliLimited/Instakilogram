<?php
require "../vendor/autoload.php";
require "../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function ($table) {
  $table->id();
  $table->string("first_name", 30);
  $table->string("last_name", 30);
  $table->string("username", 30)->unique();
  $table->string("profile_picture")->nullable();
  $table->string("bio", 150)->nullable();
  $table->string("gender", 10);
  $table->string("email", 50)->unique();
  $table->string("password");
  $table->timestamps();
});