<?php

namespace Moladoust\Rubrularavel;

use Exception;
use Moladoust\Rubrularavel\Api\Rubru;
use Moladoust\Rubrularavel\Models\RubrularavelSetting;

class Rubrularavel
{
    private $_token = '';

    public function __construct()
    {
        $token = $this->_getToken();
        if (!$token) throw new Exception('there is some error in connecting to rubru.');
        $this->_token = $token;
    }

    private function _getToken()
    {
        $token = RubrularavelSetting::first()->token;

        if ($token) {
            return $token;
        } else {
            return $this->login();
        }
    }

    public function callRequest($path, $params, $method = 'POST')
    {
        $headers = [
            'Authorization: Bearer ' . $this->_token,
        ];

        $result = Rubru::call($path, $params, $method, $headers);
        if (isset($result['status']) && $result['status'] == 401) {
            $this->login();
            $result = Rubru::call($path, $params, $method, $headers);
        }

        return $result;
    }


    public function login()
    {
        $params = [
            'username' => config('rubrularavel.username'),
            'password' => config('rubrularavel.password'),
            'captcha' => '',
        ];

        $result = Rubru::call('/authenticate', $params, 'POST');

        if ($result['id_token']) {
            $rls = RubrularavelSetting::first();
            $rls->token = $result['id_token'];
            $rls->save();

            return $result['id_token'];
        }

        return null;
    }
}
