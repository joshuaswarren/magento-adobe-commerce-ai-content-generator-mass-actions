<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterfaceFactory;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntry;
use Creatuity\AIContentMassAction\Model\GetQueueEntryList;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\Collection;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetQueueEntryListTest extends TestCase
{
    private GetQueueEntryList $getQueueEntryList;
    private CollectionProcessorInterface|MockObject $collectionProcessorMock;
    private CollectionFactory|MockObject $collectionFactoryMock;
    private AiContentQueueEntrySearchResultInterface|MockObject $searchResultsFactoryMock;

    protected function setUp(): void
    {
        $this->collectionProcessorMock = $this->createMock(CollectionProcessorInterface::class);
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->searchResultsFactoryMock = $this->createMock(AiContentQueueEntrySearchResultInterfaceFactory::class);

        $this->getQueueEntryList = new GetQueueEntryList(
            $this->collectionProcessorMock,
            $this->collectionFactoryMock,
            $this->searchResultsFactoryMock
        );
    }

    public function testExecute(): void
    {
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $collectionMock = $this->createMock(Collection::class);
        $searchResultMock = $this->createMock(AiContentQueueEntrySearchResultInterface::class);
        $items = [
            $this->createMock(AiContentQueueEntry::class),
            $this->createMock(AiContentQueueEntry::class)
        ];

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $this->collectionProcessorMock->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collectionMock);

        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $collectionMock->expects($this->once())
            ->method('getSize')
            ->willReturn(count($items));

        $this->searchResultsFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($searchResultMock);

        $searchResultMock->expects($this->once())
            ->method('setItems')
            ->with($items);

        $searchResultMock->expects($this->once())
            ->method('setTotalCount')
            ->with(count($items));

        $searchResultMock->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteria);

        $result = $this->getQueueEntryList->execute($searchCriteria);

        $this->assertSame($searchResultMock, $result);
    }
}
