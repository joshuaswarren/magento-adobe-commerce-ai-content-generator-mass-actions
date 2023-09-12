<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterfaceFactory;

class DeleteQueueEntry
{
    public function __construct(
        private readonly ResourceModel\AiContentQueueEntry $aiContentQueueEntryResource,
        private readonly AiContentQueueEntryInterfaceFactory $aiContentQueueEntryFactory
    ) {
    }

    /**
     * @param int $entryId
     * @return void
     * @throws \Exception
     */
    public function execute(int $entryId): void
    {
        $entry = $this->aiContentQueueEntryFactory->create();
        $this->aiContentQueueEntryResource->load($entry, $entryId);
        $this->aiContentQueueEntryResource->delete($entry);
    }
}