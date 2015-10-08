<?php
require 'vendor/autoload.php';

require 'config.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

$c = new \Slim\Container();
$c['foundHandler'] = function() {
    return new \Slim\Handlers\Strategies\RequestResponseArgs();
};

$principal = null;
$signer = new Sha256();

$database = new medoo($db_config);

$app = new \Slim\App($c);

// Authentication middleware
$app->add(function(ServerRequestInterface $request, ResponseInterface $response, $next) {
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
});

$app->get('/admin/rest/principal', function(ServerRequestInterface $request, ResponseInterface $response) {
    global $principal;
    if ($principal == null) {
        return $response->withStatus(401);
    } else {
        $body = $response->getBody();
        $body->write(json_encode($principal['public']));
        return $response->withHeader('Content-type', 'application/json');
    }
});

$app->post('/admin/rest/principal/authenticate', function(ServerRequestInterface $request, ResponseInterface $response) {
    global $principal;
    global $signer;
    global $database;

    $loginForm = $request->getParsedBody();
    $username = $loginForm['username'];
    $password = $loginForm['password'];

    $body = $response->getBody();

    $users = $database->select('users', ['id', 'email', 'password'], ['username' => $username]);
    if ($users !== false) {
        $stored_hash = $users[0]['password'];
        if (crypt($password, $stored_hash) == $stored_hash) {
            $principal = [
                'private' => [
                    'id' => $users[0]['id']
                ],
                'public' => [
                    'username' => $username,
                    'email' => $users[0]['email'],
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
    } else {
        $principal = null;
        $body->write(json_encode(['code' => 'ko']));
    }

    return $response->withHeader('Content-type', 'application/json');
});

$app->run();
