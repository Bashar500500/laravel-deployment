<?php

namespace App\DataTransferObjects\Profile;

use App\Http\Requests\Profile\AdminProfileRequest;

class AdminProfilePermanentAddressDto
{
    public function __construct(
        public readonly ?string $street,
        public readonly ?string $city,
        public readonly ?string $state,
        public readonly ?string $country,
        public readonly ?string $zipCode,
    ) {}

    public static function from(AdminProfileRequest $request): AdminProfilePermanentAddressDto
    {
        return new self(
            street: $request->validated('permanent_address.street'),
            city: $request->validated('permanent_address.city'),
            state: $request->validated('permanent_address.state'),
            country: $request->validated('permanent_address.country'),
            zipCode: $request->validated('permanent_address.zip_code'),
        );
    }
}
