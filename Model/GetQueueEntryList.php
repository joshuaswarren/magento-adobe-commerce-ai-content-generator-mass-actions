<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterfaceFactory;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class GetQueueEntryList
{
    public function __construct(
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly CollectionFactory $collectionFactory,
        private readonly AiContentQueueEntrySearchResultInterfaceFactory $searchResultsFactory
    ) {
    }

    public function execute(SearchCriteriaInterface $searchCriteria): AiContentQueueEntrySearchResultInterface
    {
        /** @var \Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\Collection  $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var AiContentQueueEntrySearchResultInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}