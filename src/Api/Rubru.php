<?php

namespace Moladoust\Rubrularavel\Api;

use Exception;
use Moladoust\Rubrularavel\Api\HttpRequest;
use Moladoust\Rubrularavel\Api\HttpError;


class Rubru
{
    // const VERSION = '1.0.0';

    // const ROOM_STATUS_DISABLED   = 0;
    // const ROOM_STATUS_ENABLED    = 1;

    // const USER_STATUS_DISABLED   = 0;
    // const USER_STATUS_ENABLED    = 1;

    // const USER_GENDER_UNKNOWN    = 0;
    // const USER_GENDER_MALE       = 1;
    // const USER_GENDER_FEMALE     = 2;

    // const USER_ACCESS_NORMAL     = 1;
    // const USER_ACCESS_PRESENTER  = 2;
    // const USER_ACCESS_OPERATOR   = 3;
    // const USER_ACCESS_ADMIN      = 4;

    public static function call($path, array $params, $method = 'POST', $headers = []) {
        $url = config('rubrularavel.baseUrl') . $path;

        $http = new HttpRequest($url);
        try {
            return $http->call(json_encode($params), $method, $headers);
        } catch (Exception $e) {
            // return new HttpError($e->getMessage(), $e->getCode());
            return [
                'error' => true,
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }
}