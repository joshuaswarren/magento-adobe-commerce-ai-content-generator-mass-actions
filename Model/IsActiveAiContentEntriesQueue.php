<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;

class IsActiveAiContentEntriesQueue
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory
    ) {
    }

    public function execute(): bool
    {
        return $this->collectionFactory->create()->getSize() > 0;
    }
}