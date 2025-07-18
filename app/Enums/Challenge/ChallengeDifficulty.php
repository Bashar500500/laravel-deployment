<?php

namespace App\Enums\Challenge;

enum ChallengeDifficulty: string
{
    case Easy = 'Easy';
    case Medium = 'Medium';
    case Hard = 'Hard';
    case Expert = 'Expert';
}
