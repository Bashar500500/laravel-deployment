<?php

namespace App\Enums\Plagiarism;

enum PlagiarismStatus: string
{
    case Pending = 'Pending';
    case Clear = 'Clear';
    case Flagged = 'Flagged';
}
