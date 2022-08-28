<?php

namespace App\Http\Controllers\works;

use App\Http\Controllers\Controller;
use App\Http\Controllers\works\Projects;
use App\Http\Controllers\works\Employments;
use Illuminate\Http\Request;

class Works extends Controller
{
    public function getWorks($bio_id){
        $works = [
            'projects' => $this->projects($bio_id),
            'employments' => $this->employments($bio_id),
        ];
        return response()->json(compact('works'));
    }

    protected function projects($bio_id){
        $pex = new Projects();
        return $pex->getProjects($bio_id);
    }

    protected function employments($bio_id){
        $em = new Employments();
        return $em->getEmploymentsByPeople($bio_id);
    }

    public function getEmployments($bio_id){
        $employments = $this->getEmployments($bio_id);
        return response()->json(compact('employments'));
    }
}
