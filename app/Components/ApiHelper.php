<?php

namespace App\Components;

class ApiHelper
{
    public const STATUS_OK = 200;
    public const STATUS_NO_CONTENT = 204;
    public const STATUS_BAD_REQUEST = 400;
    public const STATUS_UNAUTHORIZED = 401;
    public const STATUS_NOT_FOUND = 404;
    public const AUTH_URL_API = 'http://172.16.1.39:8181/api/users/login';
    public const USER_URL_API = 'http://172.16.1.39:8181/api/users';
    public const EVENT_URL_API = 'http://172.16.1.39:8080/api/events/class';
    public const EVENT_MODEL_URL_API = 'http://172.16.1.39:8080/api/events';
    public const USER_COUNT_URL_API = 'http://172.16.1.39:8181/api/users/count';
    public const PARTICIPANT_URL_API = 'http://172.16.1.39:8181/api/participants';
    public const PARTICIPANT_COUNT_URL_API = 'http://172.16.1.39:8181/api/participants/count';
    public const SCHOOL_URL_API = 'http://172.16.1.39:8181/api/schools';
    public const SCHOOL_COUNT_URL_API = 'http://172.16.1.39:8181/api/schools/count';
    public const APPLICATION_URL_API = 'http://172.16.0.196:8082/applications';
    public const APPLICATION_EVENT_URL_API = 'http://172.16.0.196:8082/applications/{id}';
    public const APPLICATION_COUNT_URL_API = '';
}
