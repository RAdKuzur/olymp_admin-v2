<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\SubjectDictionary;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $subjects = SubjectDictionary::getList();
        return view('report.index', compact('subjects'));
    }
    public function download($id){

        return redirect()->route('report.index');
    }

}
