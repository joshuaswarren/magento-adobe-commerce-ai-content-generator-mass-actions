<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

class AiContentQueueEntryRepository implements AiContentQueueEntryRepositoryInterface
{

    public function __construct(
        private readonly ResourceModel\AiContentQueueEntry $aiContentQueueEntryResource,
        private readonly GetQueueEntryList $getQueueEntryList,
        private readonly DeleteQueueEntry $deleteQueueEntry
    ) {
    }

    public function save(AiContentQueueEntryInterface $queueEntry): void
    {
        try {
            $this->aiContentQueueEntryResource->save($queueEntry);
        } catch (\Throwable $t) {
            throw new CouldNotSaveException(__('Failed to save queue entry', $t));
        }
    }

    public function getList(SearchCriteriaInterface $searchCriteria): AiContentQueueEntrySearchResultInterface
    {
        return $this->getQueueEntryList->execute($searchCriteria);
    }

    public function deleteById(int $entryId): void
    {
        try {
            $this->deleteQueueEntry->execute($entryId);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Queue entry could\'t be removed'), $e);
        }
    }

    public function clear(): void
    {
        /** @var \Magento\Framework\DB\Adapter\AdapterInterface $conn */
        $conn = $this->aiContentQueueEntryResource->getConnection();
        $conn->truncateTable($this->aiContentQueueEntryResource::TABLE_NAME);
    }
}