<?php

namespace Bitrix\Openid\Client\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface OpenIdHandlerInterface
{
    public function handle(ServerRequestInterface $request, OpenIdClientInterface $client, $id = null);
}
