<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class FlightExp extends Model
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

    protected $table = 'flight_experiences';
    protected $fillable = ['hours_flight', 'biodata_id', 'air_craf_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
