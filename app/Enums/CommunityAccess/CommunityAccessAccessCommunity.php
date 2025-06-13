<?php

namespace App\Enums\CommunityAccess;

enum CommunityAccessAccessCommunity: string
{
    case LoggedInUsers = 'Logged in users';
    case OnlyPayingUsers = 'Only paying users';
}
