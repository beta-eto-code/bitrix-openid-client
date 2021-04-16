<?php


namespace Bitrix\Openid\Client\Interfaces;


use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseExceptionInterface extends RequestExceptionInterface
{
    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;
}