<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yannick
 * Date: 10/10/2015
 * Time: 22:55
 */

namespace YMA;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;


class TokenFilter
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        global $principal;
        global $signer;
        global $database;

        $x_auth_token = $request->getHeader('X-Auth-Token');
        if ($x_auth_token != null) {
            $token = (new Parser())->parse($x_auth_token[0]);
            $data = new ValidationData();
            $data->setIssuer('http://cchits.net');
            $data->setAudience('http://cchits.net');
            $data->setId('bfdc1f68-93a5-4007-b951-9f5973661e07');
            if ($token->verify($signer, '944d4729-d867-49b4-b45c-8a1b16e8ed3c') and $token->validate($data)) {
                $public_principal = $token->getClaim('principal');
                $users = $database->select(
                    'users',
                    ['id', 'email'],
                    ['username' => $public_principal->username]
                );
                if ($users !== false) {
                    $principal = [
                        'private' => [
                            'id' => $users[0]['id']
                        ],
                        'public' => $public_principal
                    ];
                } else {
                    $principal = null;
                }
            } else {
                $principal = null;
            }
        } else {
            $principal = null;
        }
        return $next($request, $response);
    }
}