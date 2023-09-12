<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AiContentQueueEntrySearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface[]
     */
    public function getItems();

    /**
     * @return \Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}