<?php

namespace App\Enums\Plagiarism;

enum PlagiarismStatus: string
{
    case Pendding = 'Pendding';
    case Clear = 'Clear';
    case Flagged = 'Flagged';
}
