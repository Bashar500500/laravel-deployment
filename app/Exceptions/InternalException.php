<?php

namespace App\Exceptions;

use Exception;
use App\Enums\Exception\ExceptionCode;
use App\Enums\Trait\ModelName;

class InternalException extends Exception
{
    protected ExceptionCode $internalCode;
    protected ModelName $modelName;
    protected array $errors;
    protected string $description;

    public static function new(
        ExceptionCode $code,
        ModelName $model,
        ?string $message = null,
        ?string $description = null,
        ?array $errors = null,
        ?int $statusCode = null,
    ): static
    {
        $exception = new static(
            $message ?? $code->getMessage($model),
            $statusCode ?? $code->getStatusCode(),
        );

        $exception->internalCode = $code;
        $exception->modelName = $model;
        $exception->description = $description ?? $code->getDescription($model);
        $exception->errors = $errors ?? [];

        return $exception;
    }

    public function getDescription(): string|array
    {
        return empty($this->errors) ? $this->description : $this->errors;
    }

    public function getInternalCode(): ExceptionCode
    {
        return $this->internalCode;
    }
}
