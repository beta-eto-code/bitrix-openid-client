<?php

namespace Bitrix\Openid\Client;

use Bitrix\Openid\Client\Interfaces\OpenIdClientInterface;
use Bitrix\Openid\Client\Interfaces\OpenIdHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthCodeResolveHandler implements OpenIdHandlerInterface
{
    public function handle(ServerRequestInterface $request, OpenIdClientInterface $client, $id = null)
    {
        $queryList = $request->getQueryParams();
        $code = $queryList['code'] ?? null;
        if (empty($code)) {
            return null;
        }

        $client->getConfig()->setAuthCode($code);
    }
}
