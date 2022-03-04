<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class PilotCertificate extends Model
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
    
    protected $table = 'pilot_certificates';
    protected $fillable = ['biodata_id', 'certificate_id', 'valid_start', 'valid_end'];
    protected $hidden = ['created_at', 'updated_at'];

}
