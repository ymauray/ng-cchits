<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yannick
 * Date: 10/10/2015
 * Time: 23:05
 */

namespace YMA;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetPrincipal
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
        global $principal;
        if ($principal == null) {
            return $response->withStatus(401);
        } else {
            $body = $response->getBody();
            $body->write(json_encode($principal['public']));
            return $response->withHeader('Content-type', 'application/json');
        }
    }
}