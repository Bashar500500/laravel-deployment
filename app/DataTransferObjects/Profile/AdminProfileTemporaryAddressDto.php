<?php

namespace App\DataTransferObjects\Profile;

use App\Http\Requests\Profile\AdminProfileRequest;

class AdminProfileTemporaryAddressDto
{
    public function __construct(
        public readonly ?string $street,
        public readonly ?string $city,
        public readonly ?string $state,
        public readonly ?string $country,
        public readonly ?string $zipCode,
    ) {}

    public static function from(AdminProfileRequest $request): AdminProfileTemporaryAddressDto
    {
        return new self(
            street: $request->validated('temporary_address.street'),
            city: $request->validated('temporary_address.city'),
            state: $request->validated('temporary_address.state'),
            country: $request->validated('temporary_address.country'),
            zipCode: $request->validated('temporary_address.zip_code'),
        );
    }
}
