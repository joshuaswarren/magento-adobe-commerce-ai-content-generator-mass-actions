<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\Validator;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Validation\ValidationResult;

interface AiContentQueueEntryValidatorInterface
{
    public function validate(AiContentQueueEntryInterface $aiContentQueueEntry): ValidationResult;
}