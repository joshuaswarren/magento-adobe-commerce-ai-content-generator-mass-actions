<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\Validator;

use Creatuity\AIContent\Enum\AiContentTypeEnum;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

class AiContentQueueEntryContentTypeValidator implements AiContentQueueEntryValidatorInterface
{
    public function __construct(
        private readonly ValidationResultFactory $validationResultFactory
    ) {
    }

    public function validate(AiContentQueueEntryInterface $aiContentQueueEntry): ValidationResult
    {
        $errors = [];
        match ($aiContentQueueEntry->getContentType()) {
            AiContentTypeEnum::DESCRIPTION_GROUP->value,
            AiContentTypeEnum::META_GROUP->value => true,

            default => $errors[] = __('Unsupported content type %1', $aiContentQueueEntry->getContentType())
        };

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}