<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use Uuid;

    /**
     * get the value indicating whether the IDs are incrementing
     * 
     * @return void
     */

    public function getIncrementing(){
        return false;
     }

     /**
      * get the auto-increment key type
      *
      *@return string
      */
    public function getKeyType(){
        return 'uuid';
    }

    protected $fillable = [
        'name',
        'place_birth',
        'date_birth',
        'marrid',
        'img_av',
        'is_deleted',
        'user_id',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
    ];
}
