<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterfaceFactory;

class CreateAiContentQueueEntry
{
    public function __construct(
        private readonly AiContentQueueEntryInterfaceFactory $aiContentQueueEntryFactory,
        private readonly AiContentQueueEntryHydrator $aiContentQueueEntryHydrator
    ) {
    }

    public function create(array $data): AiContentQueueEntryInterface
    {
        $object = $this->aiContentQueueEntryFactory->create();
        $this->aiContentQueueEntryHydrator->hydrate($object, $data);

        return $object;
    }
}