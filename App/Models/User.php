<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model ;

class User extends Model
{
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'username',
    'password',
    'profile_picture',
    'bio',
    'gender'
  ];

  public function notifications() {
    return $this->hasMany(Notification::class);
  }

  public function posts() {
    return $this->hasMany(Post::class);
  }

  public function comments() {
    return $this->hasMany(Comment::class);
  }

  public function likes() {
    return $this->hasMany(Like::class);
  }

  public function settings() {
    return $this->hasOne(Setting::class);
  }
}