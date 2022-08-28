<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Biodata as Bio;
use Carbon\Carbon;

class Upload extends Controller
{
    public function uploadUsrImg(Request $req){
        $avatar = $req->file('img_av');
        $name = $avatar->hashName();
        $avatar->storeAs('public/avatars', $name);

        Bio::where('id', $req->bio_id)->update(['img_av' => $name]);

        return response()->json(['img_av' => asset('avatars/' . $name)]);
    }
}
