<?php

declare(strict_types=1);

namespace App\Exam\Service\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

#[\Attribute]
class StethoMeId extends Regex
{
    public const PATTERN = '/^[0-9A-Fa-f]{32}$/';

    public $message = 'Invalid ID value, expected format: [0-9aA-fF]{32}';

    public function __construct(string $message = null, string $htmlPattern = null, bool $match = null, callable $normalizer = null, array $groups = null, mixed $payload = null, array $options = [])
    {
        parent::__construct(self::PATTERN, $message, $htmlPattern, $match, $normalizer, $groups, $payload, $options);
    }

    public function validatedBy(): string
    {
        return RegexValidator::class;
    }
}
