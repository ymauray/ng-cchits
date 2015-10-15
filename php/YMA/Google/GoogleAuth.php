<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yannick
 * Date: 11/10/2015
 * Time: 00:31
 */

namespace YMA\Google;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use YMA\CURL;

class GoogleAuth
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {

        $curl = CURL::init('https://accounts.google.com/.well-known/openid-configuration');
        $json_response = $curl->get();
        $config = json_decode($json_response);

        $queryParams = $request->getQueryParams();
        $code = $queryParams['code'];
        if (array_key_exists('error', $queryParams)) {
            return;
        }
        $client_id = '26303522450-7avdes44tir05e45dm58dmoqho109v8f.apps.googleusercontent.com';
        $client_secret = 'wWtK7kaJcfY7eV6eX9QjQCnR';
        $redirect_uri = 'http://localhost:9090/oauth/callback/google';
        $token_endpoint = $config->token_endpoint;
        $params = [
            'code' => $code,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code'
        ];
        $curl = CURL::init($token_endpoint);
        $curl->setContentTypeFormUrlEncoded();
        $json_response = $curl->post($params);
        $authObj = json_decode($json_response);

        $accessToken = $authObj->access_token;
        $idToken = $authObj->id_token;

        $userinfo_endpoint = $config->userinfo_endpoint;
        $curl = CURL::init($userinfo_endpoint . '?access_token=' . $accessToken);
        $json_response = $curl->get();
        $userInfoObject = json_decode($json_response);
        $email = $userInfoObject->email;
    }
}