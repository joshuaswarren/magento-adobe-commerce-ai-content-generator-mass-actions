<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Api;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

interface AiContentQueueEntryRepositoryInterface
{
    /**
     * @param AiContentQueueEntryInterface $queueEntry
     * @throws CouldNotSaveException
     * @return void
     */
    public function save(AiContentQueueEntryInterface $queueEntry): void;

    /**
     * @param int $entryId
     * @throws CouldNotDeleteException
     * @return void
     */
    public function deleteById(int $entryId): void;

    /**
     * @return void
     */
    public function clear(): void;
}