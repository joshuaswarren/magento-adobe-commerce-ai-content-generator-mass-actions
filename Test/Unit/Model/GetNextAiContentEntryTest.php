<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\Collection;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetNextAiContentEntryTest extends TestCase
{
    private readonly GetNextAiContentEntry $getNextAiContentEntry;
    private readonly CollectionFactory|MockObject $collectionFactoryMock;

    protected function setUp(): void
    {
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);

        $this->getNextAiContentEntry = new GetNextAiContentEntry(
            $this->collectionFactoryMock
        );
    }

    public function testExecuteWithId(): void
    {
        $startFrom = 5;
        $collectionMock = $this->createMock(Collection::class);
        $mockEntry = $this->createMock(AiContentQueueEntryInterface::class);

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $collectionMock->expects($this->once())
            ->method('addFieldToFilter')
            ->with(AiContentQueueEntryInterface::ID, ['gt' => $startFrom]);

        $collectionMock->expects($this->once())
            ->method('setOrder')
            ->with(AiContentQueueEntryInterface::ID, $collectionMock::SORT_ORDER_ASC);

        $collectionMock->expects($this->once())
            ->method('setPageSize')
            ->with(1);

        $collectionMock->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($mockEntry);

        $mockEntry->expects($this->once())
            ->method('getId')
            ->willReturn(6);

        $result = $this->getNextAiContentEntry->execute($startFrom);

        $this->assertSame($mockEntry, $result);
    }

    public function testExecuteWithoutId(): void
    {
        $collectionMock = $this->createMock(Collection::class);
        $mockEntry = $this->createMock(AiContentQueueEntryInterface::class);

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $collectionMock->expects($this->once())
            ->method('setOrder')
            ->with(AiContentQueueEntryInterface::ID, $collectionMock::SORT_ORDER_ASC);

        $collectionMock->expects($this->once())
            ->method('setPageSize')
            ->with(1);

        $collectionMock->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($mockEntry);

        $mockEntry->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $result = $this->getNextAiContentEntry->execute();

        $this->assertSame($mockEntry, $result);
    }

    public function testExecuteNoResult(): void
    {
        $collectionMock = $this->createMock(Collection::class);
        $mockEntry = $this->createMock(AiContentQueueEntryInterface::class);

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $collectionMock->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($mockEntry);

        $mockEntry->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $result = $this->getNextAiContentEntry->execute();

        $this->assertNull($result);
    }
}
