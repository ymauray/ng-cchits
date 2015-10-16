<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yannick
 * Date: 10/10/2015
 * Time: 23:07
 */

namespace YMA;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lcobucci\JWT\Builder;

class Authenticate
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
        global $principal;
        global $signer;
        global $database;

        $loginForm = $request->getParsedBody();
        $username = $loginForm['username'];
        $password = $loginForm['password'];

        $body = $response->getBody();

        $users = $database->select('users', ['intUserID', 'strEmail', 'isAuthorized'], ['sha1Pass' => $username . ':' . $password]);
        if ($users !== false) {
            $principal = [
                'private' => [
                    'id' => $users[0]['intUserID']
                ],
                'public' => [
                    'username' => $username,
                    'email' => $users[0]['strEmail'],
                    'roles' => ['ADMIN', 'USER']
                ]
            ];

            //$now = time();
            $token = (new Builder())
                ->setIssuer('http://cchits.net')                        // Configures the issuer
                ->setAudience('http://cchits.net')                      // Configures the audience
                ->setId('bfdc1f68-93a5-4007-b951-9f5973661e07', true)   // Configures the id
                //->setIssuedAt($now)                                   // Configures the time that the token was issued
                //->setNotBefore($now)                                  // Configures the time that the token can be used
                //->setExpiration($now + 900)                           // Configures the expiration time of the token (15 minutes)
                ->set('principal', $principal['public'])
                ->sign($signer, '944d4729-d867-49b4-b45c-8a1b16e8ed3c') // creates a signature using the given key
                ->getToken();

            $string_token = '' . $token;
            $body->write(json_encode(['code' => 'ok', 'token' => $string_token]));
        } else {
            $principal = null;
            $body->write(json_encode(['code' => 'ko']));
        }

        return $response->withHeader('Content-type', 'application/json');
    }
}