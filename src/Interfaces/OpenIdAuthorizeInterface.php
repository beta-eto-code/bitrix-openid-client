<?php

namespace Bitrix\Openid\Client\Interfaces;

use Bitrix\Main\Result;

interface OpenIdAuthorizeInterface
{
    /**
     * @param null $id
     * @return Result
     */
    public function authorize($id = null): Result;

    /**
     * @return bool
     */
    public function isAuthorized(): bool;
}
