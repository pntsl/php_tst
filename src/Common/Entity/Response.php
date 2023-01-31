<?php
namespace Common\Entity;


class Response
{
    protected function __construct(
        protected array|null $payload = null,
        protected array|null $error = null,
        protected int|null $statusCode = null,
    ) {
    }

    public static function getSuccessResponse(
        array $payload,
        int|null $statusCode = null,
    ): array
    {
        return (new self(
            payload: $payload,
            statusCode: $statusCode,
        ))
            ->__toArray();
    }

    public static function getErrorResponse(
        array $error,
        int|null $statusCode = null,
    ): array
    {
        return (new self(
            error: $error,
            statusCode: $statusCode,
        ))
            ->__toArray();
    }

    protected function __toArray()
    {
        return [
            [
                'payload' => $this->payload ?: null,
                'error' => $this->error ?: null,
            ],
            $this->statusCode ?? 0,
        ];
    }
}
