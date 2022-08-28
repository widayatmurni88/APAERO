<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\biodatas\Biodata as bio;
use App\Http\Controllers\flightExps\FlightExp as fexp;
use App\Http\Controllers\pilotInCommands\PilotInCommand as pic;
use App\Http\Controllers\pilotTypeOfOperations\PilotTypeOfOperation as top;

class Dashboard extends Controller {
    protected function getPeoples(){
        $people = new bio();
        $peoples = $people->getPeoplesWithFlightNum();
        return $peoples;
    }

    protected function getFExp($peopleId){
        $fexp = new fexp();
        return $fexp->getTotalFExpByPeople($peopleId)['hours_flight'];
    }

    protected function getPics($peopleId){
        $pic = new pic();
        return $pic->getPicsById($peopleId)['hours_flight'];
    }

    protected function getTop($peopleId){
        $top = new top();
        return $top->getTopById($peopleId)['hours_operation'];
    }

    public function index(){
        $people = $this->getPeoples();
        $peoples = [];
        
        foreach ($people as $p) {
            $tmp = [
                'id' => $p->id,
                'name' => $p->name,
                'licence_number' => $p->licence_number,
                'fexps' => [
                    'fexp' => $this->getFexp($p->id),
                    'pic' => $this->getPics($p->id),
                    'top' => $this->getTop($p->id),
                ],
            ];
            $peoples = Arr::prepend($peoples, $tmp);
        }

        return response()->json(compact('peoples'));
    }
}
