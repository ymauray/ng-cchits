<?php
require 'vendor/autoload.php';

require_once 'config.php';

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Slim\Container;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\App;
use YMA\TokenFilter;
use YMA\GetPrincipal;
use YMA\Authenticate;
use YMA\Google\GoogleConfig;
use YMA\Google\GoogleAuth;

$c = new Container();
$c['foundHandler'] = function() {
    return new RequestResponseArgs();
};

$principal = null;
$signer = new Sha256();
$database = new medoo($db_config);

$app = new App($c);

// Authentication middleware
$app->add(new TokenFilter());
$app->get('/admin/rest/principal', new GetPrincipal());
$app->post('/admin/rest/principal/authenticate', new Authenticate());
$app->get('/admin/rest/oauth/google/config', new GoogleConfig());
$app->get('/oauth/callback/google', new GoogleAuth());
$app->run();
