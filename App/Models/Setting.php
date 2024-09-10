<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model ;

class Setting extends Model
{
  public $timestamps = false;
  protected $fillable = [
    'user_id',
    'prefered_theme',
  ];
}