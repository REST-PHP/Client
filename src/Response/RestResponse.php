<?php

declare(strict_types=1);

namespace Rest\Http\Response;

use Rest\Http\Request\RestRequest;
use Rest\Http\Status;

final class RestResponse
{
    private Status $status;

    private RestRequest $request;

    private array $headers = [];

    private ?string $content;

    public static function new(Status $status, RestRequest $request): self
    {
        return new self($status, $request);
    }

    public function __construct(Status $status, RestRequest $request)
    {
        $this
            ->setStatus($status)
            ->setRequest($request);
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
    }

    public function getRequest(): RestRequest
    {
        return $this->request;
    }

    public function setRequest(RestRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}