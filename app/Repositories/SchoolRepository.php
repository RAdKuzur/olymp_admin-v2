<?php

namespace App\Repositories;

use App\Services\ApiService;
use Illuminate\Support\Facades\Cookie;

class SchoolRepository
{
    private ApiService $apiService;
    public function __construct(
        ApiService $apiService
    )
    {
        $this->apiService = $apiService;
    }

    public function getByApiAll($page = 1, $limit = 10)
    {
        return $this->apiService->get(
            'http://172.16.1.39:8181/api/schools' . '?page=' . $page . '&limit=' . $limit,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
    }
    public function getByApiId($id)
    {
        return $this->apiService->get(
            'http://172.16.1.39:8181/api/schools' . '/' . $id,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token,
            ]
        );
    }
    public function getCount()
    {
        $response = $this->apiService->get(
            'http://172.16.1.39:8181/api/schools/count',
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token,
            ]
        );
        return $response['data']['data'];
    }
}
