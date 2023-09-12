<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;

class GetNextAiContentEntry
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory
    ) {
    }

    public function execute(?int $startFrom = null): ?AiContentQueueEntryInterface
    {
        $collection = $this->collectionFactory->create();
        if ($startFrom) {
            $collection->addFieldToFilter(AiContentQueueEntryInterface::ID, ['gt' => $startFrom]);
        }
        $collection->setOrder(AiContentQueueEntryInterface::ID, $collection::SORT_ORDER_ASC);
        $collection->setPageSize(1);

        /** @var AiContentQueueEntryInterface $entry */
        $entry = $collection->getFirstItem();

        return $entry->getId() ? $entry : null;
    }
}