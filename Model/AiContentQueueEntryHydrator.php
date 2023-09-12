<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class AiContentQueueEntryHydrator
{
    public function __construct(
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly AiContentQueueEntryInterfaceFactory $aiContentQueueEntryFactory
    ) {
    }

    public function hydrate(array $data): AiContentQueueEntryInterface
    {
        $object = $this->aiContentQueueEntryFactory->create();
        $this->dataObjectHelper->populateWithArray($object, $data, AiContentQueueEntryInterface::class);

        return $object;
    }
}