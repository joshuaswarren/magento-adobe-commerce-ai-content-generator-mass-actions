<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Model\IsActiveAiContentEntriesQueue;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\Collection;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IsActiveAiContentEntriesQueueTest extends TestCase
{
    private readonly IsActiveAiContentEntriesQueue $isActiveAiContentEntriesQueue;
    private readonly CollectionFactory|MockObject $collectionFactoryMock;

    protected function setUp(): void
    {
        $this->collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $this->isActiveAiContentEntriesQueue = new IsActiveAiContentEntriesQueue($this->collectionFactoryMock);
    }

    public function testExecuteReturnsTrueWhenQueueIsActive(): void
    {
        $collectionMock = $this->createMock(Collection::class);

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $collectionMock->expects($this->once())
            ->method('getSize')
            ->willReturn(5);

        $this->assertTrue($this->isActiveAiContentEntriesQueue->execute());
    }

    public function testExecuteReturnsFalseWhenQueueIsEmpty(): void
    {
        $collectionMock = $this->createMock(Collection::class);

        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);

        $collectionMock->expects($this->once())
            ->method('getSize')
            ->willReturn(0);

        $this->assertFalse($this->isActiveAiContentEntriesQueue->execute());
    }
}
