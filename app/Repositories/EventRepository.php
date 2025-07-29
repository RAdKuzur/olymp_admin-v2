<?php

namespace App\Repositories;

use App\Components\ApiHelper;
use App\Services\ApiService;
use Illuminate\Support\Facades\Cookie;

class EventRepository
{
    private ApiService $apiService;
    public function __construct(ApiService $apiService){
        $this->apiService = $apiService;
    }
    public function getByApiAll($page = 1, $limit = 10)
    {
        $response = $this->apiService->get(
            ApiHelper::EVENT_URL_API,
            [
                'page' => $page,
                'limit' => $limit
            ],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
        return $response['data']['data'];
    }
    public function getByApiId($id)
    {
        $response = $this->apiService->get(
            ApiHelper::EVENT_MODEL_URL_API . '/' . $id,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
        return $response['data']['data'];
    }
    public function getCount()
    {
        $response = $this->apiService->get(
            ApiHelper::EVENT_URL_API,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
        return $response['data']['data']['totalCount'];
    }
}
