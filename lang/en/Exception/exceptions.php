<?php

return [
    '403' => [
        'message, :Model' => 'Forbidden.',
        'description, :Model' => 'Received data conflicts with data stored in database. Try again with correct data.'
    ],
    '401' => [
        'message, :Model' => 'Unauthorized.',
        'description, :Model' => 'Invalid credentials. Try again later.'
    ],
    '402' => [
        'message, :Model' => 'Something went wrong.',
        'description, :Model' => 'Got an error from firebase. Try again later.'
    ],
    '409' => [
        'message, :Model' => ':Model already exists.',
        'description, :Model' => 'A :Model with this data has already been created. You cannot create duplicates.'
    ],
    '404' => [
        'message, :Model' => ':Model not found.',
        'description, :Model' => 'A :Model with this data not found. You cannot retrive it.'
    ],
    '500' => [
        'message, :Model' => 'Server error.',
        'description, :Model' => 'A :Model is down. Try again later.'
    ],
];
