<?php

namespace App\Enums\Request;

enum ValidationType: string
{
    case Required = 'required';
    case Enum = 'enum';
    case Exists = 'exists';
    case Integer = 'integer';
    case GreaterThanZero = 'gt_0';
    case GreaterThan = 'gt';
    case GreaterThanOrEqualZero = 'gte_0';
    case GreaterThanOrEqual = 'gte';
    case LessThanOrEqual = 'lte';
    case LessThan = 'lt';
    case String = 'string';
    case Boolean = 'boolean';
    case Regex = 'regex';
    case Date = 'date';
    case DateFormat = 'date_format';
    case AfterOrEqual = 'after_or_equal';
    case Image = 'image';
    case ImageMimes = 'image_mimes';
    case Decimal = 'decimal';
    case RequiredIf = 'required_if';
    case MissingIf = 'missing_if';
    case Array = 'array';
    case File = 'file';
    case Same = 'same';
    case PdfMimes = 'pdf_mimes';
    case VideoMimes = 'video_mimes';
    case RequiredWith = 'required_with';
    case Url = 'url';
    case TimeFormat = 'time_format';
    case Min = 'min';
    case Max = 'max';
    case Uuid = 'uuid';
    case After = 'after';
    case Email = 'email';
    case Unique = 'unique';
    case Confirmed = 'confirmed';

    public function getMessage(): string
    {
        $key = "Request/validations.{$this->value}.message";
        $translation = __($key);

        if ($key == $translation)
        {
            return "Something went wrong";
        }

        return $translation;
    }
}
