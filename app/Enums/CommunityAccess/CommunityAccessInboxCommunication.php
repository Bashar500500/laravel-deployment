<?php

namespace App\Enums\CommunityAccess;

enum CommunityAccessInboxCommunication: string
{
    case LearnersCanMessageAnyone = 'Learners can message anyone';
    case LearnersCanOnlyMessageAdmins = 'Learners can only message admins';
    case NoInboxAccessAtAll = 'No inbox access at all';
}
