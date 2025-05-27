<?php

namespace App\Enums\Exception;

use App\Enums\Trait\ModelName;

enum ExceptionCode: int
{
    case BadRequest = 400;
    case Unauthorized = 401;
    case Firebase = 402;
    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowd = 405;
    case AlreadyExists = 409;
    case UnprocessableContent = 422;
    case InternalServerError = 500;
    case ServiceUnavailable = 503;

    public function getStatusCode(): int
    {
        return match ($this) {
            self::BadRequest => 400,
            self::Unauthorized => 401,
            self::Firebase => 402,
            self::Forbidden => 403,
            self::NotFound => 404,
            self::MethodNotAllowd => 405,
            self::AlreadyExists => 409,
            self::UnprocessableContent => 422,
            self::InternalServerError => 500,
            self::ServiceUnavailable => 503,
        };
    }

    public function getMessage(ModelName $model): string
    {
        $key = "Exception/exceptions.{$this->value}.message, :Model";
        $translation = __($key, ["Model" => $model->getMessage()]);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }

    public function getDescription(ModelName $model): string
    {
        $key = "Exception/exceptions.{$this->value}.description, :Model";
        $translation = __($key, ["Model" => $model->getMessage()]);

        if ($key == $translation)
        {
            return "No additional description provided";
        }

        return $translation;
    }

    public function getLink(): string
    {
        return route('docs.exceptions', [
            'code' => $this->value,
        ]);
    }
}
