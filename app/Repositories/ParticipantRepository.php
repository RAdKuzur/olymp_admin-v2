<?php

namespace App\Repositories;

use App\Components\ApiHelper;
use App\Services\ApiService;
use Illuminate\Support\Facades\Cookie;

class ParticipantRepository
{
    private ApiService $apiService;
    public function __construct(ApiService $apiService){
        $this->apiService = $apiService;
    }
    public function getByApiAll($page = 1, $limit = 10)
    {
        return $this->apiService->get(
            ApiHelper::PARTICIPANT_URL_API,
            [
                'page' => $page,
                'limit' => $limit
            ],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
    }
    public function getByApiId($id)
    {
        return $this->apiService->get(
            ApiHelper::PARTICIPANT_URL_API . '/' . $id,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
    }
    public function getByApiUserId($id)
    {
        return $this->apiService->get(
            ApiHelper::PARTICIPANT_BY_USER_URL_API . '/' . $id,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
    }
    public function getCount()
    {
        $response = $this->apiService->get(
            ApiHelper::PARTICIPANT_COUNT_URL_API,
            [],
            [
                'Authorization' => "Bearer ". json_decode(Cookie::get('username'))->token
            ]
        );
        return $response['data']['data'];
    }
}
