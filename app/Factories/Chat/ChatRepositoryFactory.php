<?php

namespace App\Factories\Chat;

use Illuminate\Contracts\Container\Container;
use App\Enums\Chat\ChatType;
use App\Repositories\Chat\ChatRepositoryInterface;
use App\Repositories\Chat\GroupChatRepository;
use App\Repositories\Chat\DirectChatRepository;

class ChatRepositoryFactory
{
    public function __construct(
        protected Container $container,
    ) {}

    public function make(ChatType $type): ChatRepositoryInterface
    {
        return match ($type) {
            ChatType::Group => $this->container->make(GroupChatRepository::class),
            ChatType::Direct => $this->container->make(DirectChatRepository::class),
        };
    }
}
