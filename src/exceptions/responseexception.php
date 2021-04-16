<?php


namespace Bitrix\Openid\Client\Exceptions;


use Bitrix\Openid\Client\Interfaces\ResponseExceptionInterface;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ResponseException extends Exception implements ResponseExceptionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * ResponseException constructor.
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        RequestInterface  $request,
        ResponseInterface $response,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        $this->request = $request;
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}