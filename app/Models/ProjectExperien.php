<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ProjectExperien extends Model
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

    protected $table = 'pilot_project_experiences';
    protected $fillable = ['name', 'date_start', 'date_end', 'biodata_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
