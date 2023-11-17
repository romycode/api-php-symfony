<?php

declare(strict_types=1);

namespace App\Shared\Domain;

/** @psalm-suppress UnusedClass */
class DomainException extends \DomainException
{
    public function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
