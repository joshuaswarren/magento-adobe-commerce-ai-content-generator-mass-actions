<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\EntityManager\HydratorInterface;

class AiContentQueueEntryHydrator implements HydratorInterface
{
    public function __construct(
        private readonly DataObjectHelper $dataObjectHelper
    ) {
    }

    /**
     * @param AiContentQueueEntryInterface $entity
     * @param array $data
     * @return AiContentQueueEntryInterface
     */
    public function hydrate($entity, array $data): AiContentQueueEntryInterface
    {
        $this->dataObjectHelper->populateWithArray($entity, $data, AiContentQueueEntryInterface::class);

        return $entity;
    }

    public function extract($entity): array
    {
        return $entity->getData();
    }
}