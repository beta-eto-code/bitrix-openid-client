<?php

namespace Bitrix\Openid\Client\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface OpenIdHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param OpenIdClientInterface $client
     * @param mixed $id
     * @return void
     */
    public function handle(ServerRequestInterface $request, OpenIdClientInterface $client, $id = null);
}
