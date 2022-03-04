<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class PilotInCommand extends Model
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

    protected $table = 'pilot_in_commands';
    protected $fillable = ['hours_flight', 'biodata_id', 'air_craft_id'];
}
