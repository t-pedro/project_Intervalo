<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Afirmation;

class AfirmationController extends Controller
{
    public function get_afirmations($competencias) //get_afirmations
    {
        
        //$competencias =  explode(",", $competencias);
        $ids    = [];
        $result = [];
        $output = [];

        foreach($competencias as $c){
            $result = Afirmation::wherehas('competencias',function($query) use ($c){
                                                            $query = $query->where('competencia_id', $c);
                                                        })
                                ->whereNotIn('id',$ids)
                                ->limit(15)
                                ->get()->toArray();
            
            $result_final = array_map(function($r) use ($c) {
                                        $r['id_competencia'] = $c;
                                        return $r;
                                        
                                    },$result);

            $output = array_merge($output, $result_final);
            
            $ids = $ids + array_map(function($r){ return ['id' => $r['id']]; }, $result_final);
        
        }
        return $output;
        //return  Inertia::render('Web/Quiz',['afirmations' => $output ]);
    }
}
