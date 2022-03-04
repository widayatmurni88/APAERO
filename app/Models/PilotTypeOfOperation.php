<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class PilotTypeOfOperation extends Model
{
    // use HasFactory;
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

    protected $table = 'pilot_of_operations';
    protected $fillable = ['hours_operation', 'biodata_id', 'type_of_operation_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
