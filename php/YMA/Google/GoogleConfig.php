<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yannick
 * Date: 10/10/2015
 * Time: 23:29
 */

namespace YMA\Google;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use YMA\CURL;

class GoogleConfig
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {

        $curl = CURL::init('https://accounts.google.com/.well-known/openid-configuration');
        $json_response = $curl->get();
        $config = json_decode($json_response);

        $authorization_endpoint = $config->authorization_endpoint;

        $body = $response->getBody();
        $body->write(json_encode([
            'code' => 'ok',
            'url' => $authorization_endpoint . '?client_id=26303522450-7avdes44tir05e45dm58dmoqho109v8f.apps.googleusercontent.com&response_type=code&scope=email&redirect_uri=http://localhost:9090/oauth/callback/google'
        ]));
    }
}