<?php

namespace App\Exceptions;

use App\Enums\Exception\ExceptionCode;
use App\Enums\Exception\ForbiddenExceptionMessage;
use App\Enums\Trait\ModelName;

class CustomException extends InternalException
{
    public static function alreadyExists(ModelName $model): self
    {
        return static::new(
            ExceptionCode::AlreadyExists,
            $model,
        );
    }

    public static function forbidden(ModelName $model, ForbiddenExceptionMessage $exception): self
    {
        return static::new(
            ExceptionCode::Forbidden,
            $model,
            null,
            $exception->getDescription(),
        );
    }

    public static function notFound(string $model): self
    {
        return static::new(
            ExceptionCode::NotFound,
            ModelName::getEnum($model),
        );
    }

    public static function unprocessableContent(string $message, array $errors): self
    {
        return static::new(
            ExceptionCode::UnprocessableContent,
            ModelName::NoName,
            $message,
            null,
            $errors,
        );
    }

    public static function firebase(): self
    {
        return static::new(
            ExceptionCode::Firebase,
            ModelName::NoName,
        );
    }

    public static function internalServerError(): self
    {
        return static::new(
            ExceptionCode::InternalServerError,
            ModelName::Website,
        );
    }

    public static function unauthorized(ModelName $model): self
    {
        return static::new(
            ExceptionCode::Unauthorized,
            $model,
        );
    }

    public static function BadRequest(ModelName $model): self
    {
        return static::new(
            ExceptionCode::BadRequest,
            $model,
        );
    }

}
