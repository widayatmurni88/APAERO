<?php

namespace App\Http\Controllers\works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectExperien as pex;

class Projects extends Controller
{
    public function getProjects($bio_id){
        return pex::where('biodata_id', $bio_id)->orderBy('created_at', 'DESC')->get();
    }

    public function addProject(Request $req){
        $data = [
            'biodata_id' => $req->bio_id,
            'name' => $req->name,
            'date_start' => $req->date_start ?? null,
            'date_end' => $req->date_end ?? null,
        ];
        $project = pex::create($data);
        return response()->json(compact('project'));
    }

    public function removeById($item_id){
        $project = pex::where('id', $item_id)->delete();
        return response()->json(compact('project'));
    }
}
