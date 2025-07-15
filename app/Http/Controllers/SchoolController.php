<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\RegionDictionary;
use App\Components\RabbitMQComponent;
use App\Http\Requests\SchoolRequest;
use App\Repositories\SchoolRepository;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    private SchoolRepository $schoolRepository;
    private SchoolService $schoolService;
    public function __construct(
        SchoolRepository $schoolRepository,
        SchoolService $schoolService
    )
    {
        $this->schoolRepository = $schoolRepository;
        $this->schoolService = $schoolService;
    }

    public function index($page = 1){
        $schoolsJson = $this->schoolRepository->getByApiAll($page);
        $schools = $this->schoolService->transform($schoolsJson);
        $schoolsAmount = $this->schoolRepository->getCount();
        $regions = RegionDictionary::REGIONS;
        return view('school/index')->with('schools', $schools)->with('schoolsAmount', $schoolsAmount)->with('regions', $regions);
    }
    public function create(){
        $regions = RegionDictionary::REGIONS;
        return view('school/create')->with('regions', $regions);
    }
    public function store(SchoolRequest $request){
        $data = $request->validated();
        return redirect('/school/index');
    }
    public function show($id){
        $modelJson = $this->schoolRepository->getByApiId($id);
        $model = $this->schoolService->transformModel($modelJson);
        $regions = RegionDictionary::REGIONS;
        return view('school/show')->with('model', $model)->with('regions', $regions);
    }
    public function edit($id){
        $modelJson = $this->schoolRepository->getByApiId($id);
        $model = $this->schoolService->transformModel($modelJson);
        $regions = RegionDictionary::REGIONS;
        return view('school/edit')->with('model', $model)->with('regions', $regions);
    }
    public function update(Request $request, $id){
        $regions = RegionDictionary::REGIONS;
        return redirect('school/index')->with('regions', $regions);
    }
    public function delete($id){
        return redirect('/school/index');
    }
}
