<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid {
  /**
   * The 'booting' function of model
   * 
   * @return void
   */

  protected static function boot(){
    parent::boot();
    static::creating(function ($model){
        if (! $model->getKey()) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        }
    });
  }
}