<?php

namespace Bitrix\Openid\Client\Interfaces;

use Bitrix\Main\Result;
use Psr\Http\Message\ServerRequestInterface;

interface OpenIdAuthorizeInterface
{
    /**
     * @param mixed $id
     * @param ServerRequestInterface|null $request
     * @return Result
     * @psalm-suppress UndefinedClass
     */
    public function authorize($id = null, ?ServerRequestInterface $request = null): Result;

    /**
     * @return bool
     */
    public function isAuthorized(): bool;
}
