<?php

namespace App\DataTransferObjects\CommunityAccess;

use App\Http\Requests\CommunityAccess\CommunityAccessRequest;
use App\Enums\CommunityAccess\CommunityAccessAccessCommunity;

class CommunityAccessCommunityDto
{
    public function __construct(
        public readonly ?bool $communityEnabled,
        public readonly ?CommunityAccessAccessCommunity $accessCommunity,
    ) {}

    public static function from(CommunityAccessRequest $request): CommunityAccessCommunityDto
    {
        return new self(
            communityEnabled: $request->validated('community.community_enabled'),
            accessCommunity: $request->validated('community.access_community') ?
                CommunityAccessAccessCommunity::from($request->validated('community.access_community')) :
                null,
        );
    }
}
