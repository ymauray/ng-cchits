<?php
require 'vendor/autoload.php';

require_once 'config.php';
require_once 'php/YMA/curl.php';
require_once 'php/YMA/TokenFilter.php';
require_once 'php/YMA/GetPrincipal.php';
require_once 'php/YMA/Authenticate.php';
require_once 'php/YMA/Google/GoogleConfig.php';
require_once 'php/YMA/Google/GoogleAuth.php';

use Lcobucci\JWT\Signer\Hmac\Sha256;

$c = new \Slim\Container();
$c['foundHandler'] = function() {
    return new \Slim\Handlers\Strategies\RequestResponseArgs();
};

$principal = null;
$signer = new Sha256();
$database = new medoo($db_config);

$app = new \Slim\App($c);

// Authentication middleware
$app->add(new \YMA\TokenFilter());
$app->get('/admin/rest/principal', new \YMA\GetPrincipal());
$app->post('/admin/rest/principal/authenticate', new \YMA\Authenticate());
$app->get('/admin/rest/oauth/google/config', new \YMA\Google\GoogleConfig());
$app->get('/oauth/callback/google', new \YMA\Google\GoogleAuth());
$app->run();
