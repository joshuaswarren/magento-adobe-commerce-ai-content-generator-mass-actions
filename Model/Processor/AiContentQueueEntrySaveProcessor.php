<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\Processor;

use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\CreateAiContentQueueEntry;
use Creatuity\AIContentMassAction\Model\Validator\AiContentQueueEntryValidatorInterface;
use Magento\Framework\Validation\ValidationException;

class AiContentQueueEntrySaveProcessor
{
    public function __construct(
        private readonly AiContentQueueEntryValidatorInterface $aiContentQueueEntryValidator,
        private readonly CreateAiContentQueueEntry $createAiContentQueueEntry,
        private readonly AiContentQueueEntryRepositoryInterface $aiContentQueueEntryRepository
    ) {
    }

    /**
     * @param array $params
     * @return AiContentQueueEntryInterface
     * @throws ValidationException
     */
    public function process(array $params): AiContentQueueEntryInterface
    {
        $entry = $this->createAiContentQueueEntry->create([
            AiContentQueueEntryInterface::PRODUCT_ID => $params['product_id'],
            AiContentQueueEntryInterface::CONTENT_TYPE => $params['content_type']
        ]);

        $validationResult = $this->aiContentQueueEntryValidator->validate($entry);

        if (!$validationResult->isValid()) {
            throw new ValidationException(phrase: __('Validation failed'), validationResult: $validationResult);
        }

        $this->aiContentQueueEntryRepository->save($entry);

        return $entry;
    }
}