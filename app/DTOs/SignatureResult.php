<?php

namespace App\DTOs;

final class SignatureResult
{
    public function __construct(
        public readonly string $signature,
        public readonly string $publicKey,
    ) {}

    public function toArray(): array
    {
        return [
            'signature'  => $this->signature,
            'public_key' => $this->publicKey,
        ];
    }
}