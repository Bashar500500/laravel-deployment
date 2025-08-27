<?php

return [
    'required' => [
        'message' => 'The :attribute field is required.',
    ],
    'enum' => [
        'message' => 'The selected :attribute is invalid.',
    ],
    'exists' => [
        'message' => 'The selected :attribute is invalid.',
    ],
    'integer' => [
        'message' => 'The :attribute field must be an integer.',
    ],
    'gt_0' => [
        'message' => 'The :attribute field must be greater than 0.',
    ],
    'gt' => [
        'message' => 'The :attribute field must be greater than previous data.',
    ],
    'gte_0' => [
        'message' => 'The :attribute field must be greater than or equal to 0.',
    ],
    'gte' => [
        'message' => 'The :attribute field must be greater than or equal to previous data.',
    ],
    'lte' => [
        'message' => 'The :attribute field must be less than or equalto previous data.',
    ],
    'lt' => [
        'message' => 'The :attribute field must be less than previous data.',
    ],
    'string' => [
        'message' => 'The :attribute field must be a string.',
    ],
    'boolean' => [
        'message' => 'The :attribute field must be true or false.',
    ],
    'regex' => [
        'message' => 'The :attribute field format is invalid.',
    ],
    'date' => [
        'message' => 'The :attribute field must be a valid date.',
    ],
    'date_format' => [
        'message' => 'The :attribute field must match the format Y-m-d.',
    ],
    'after_or_equal' => [
        'message' => 'The :attribute field must be a date after or equal to previous data.',
    ],
    'image' => [
        'message' => 'The :attribute field must be an image.',
    ],
    'image_mimes' => [
        'message' => 'The :attribute field must be a file of type: jpg, jpeg, png, bmp, gif, svg, webp.',
    ],
    'decimal' => [
        'message' => 'The :attribute field must have 0-2 decimal places.',
    ],
    'required_if' => [
        'message' => 'The :attribute field is required',
    ],
    'missing_if' => [
        'message' => 'The :attribute field must be missing',
    ],
    'array' => [
        'message' => 'The :attribute field must be an array.',
    ],
    'file' => [
        'message' => 'The :attribute field must be a file.',
    ],
    'same' => [
        'message' => 'The :attribute field must match previous data.',
    ],
    'pdf_mimes' => [
        'message' => 'The :attribute field must be a file of type: pdf.',
    ],
    'video_mimes' => [
        'message' => 'The :attribute field must be a file of type: mp4, mov, ogg, qt, ogx, oga, ogv, webm.',
    ],
    'required_with' => [
        'message' => 'The :attribute field is required.',
    ],
    'url' => [
        'message' => 'The :attribute field must be a valid URL.',
    ],
    'time_format' => [
        'message' => 'The :attribute field must match the format H:i A.',
    ],
    'min' => [
        'message' => 'The :attribute field is not correct',
    ],
    'max' => [
        'message' => 'The :attribute field is not correct',
    ],
    'uuid' => [
        'message' => 'The :attribute field must be a valid UUID.',
    ],
    'after' => [
        'message' => 'The :attribute field must be a date after previous data.',
    ],
    'email' => [
        'message' => 'The :attribute field must be a valid email address.',
    ],
    'unique' => [
        'message' => 'The :attribute has already been taken.',
    ],
    'confirmed' => [
        'message' => 'The :attribute field confirmation does not match.',
    ],
];
