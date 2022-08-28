<?php

namespace App\Http\Controllers\works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployHistory as emhis;

class Employments extends Controller {
    public function getEmploymentsByPeople($bio_id) {
        return emhis::where('biodata_id',$bio_id)->orderBy('created_at', 'DESC')->get();
    }

    public function addEmployment(Request $req) {
        $data = [
            'biodata_id' => $req->bio_id,
            'name' => $req->name,
            'date_start' => $req->date_start ?? null,
            'date_end' => $req->date_end ?? null,
        ];
        $employment = emhis::create($data);
        return response()->json(compact('employment'));
    }

    public function removeById($item_id){
        $employment = emhis::where('id', $item_id)->delete();
        return response()->json(compact('employment'));
    }
}
