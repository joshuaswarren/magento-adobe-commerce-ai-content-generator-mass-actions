<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntry;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntryHydrator;
use Magento\Framework\Api\DataObjectHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AiContentQueueEntryHydratorTest extends TestCase
{
    private readonly AiContentQueueEntryHydrator $hydrator;
    private readonly DataObjectHelper|MockObject $dataObjectHelperMock;

    protected function setUp(): void
    {
        $this->dataObjectHelperMock = $this->createMock(DataObjectHelper::class);
        $this->hydrator = new AiContentQueueEntryHydrator($this->dataObjectHelperMock);
    }

    public function testHydrate(): void
    {
        $entity = $this->createMock(AiContentQueueEntryInterface::class);
        $data = ['someKey' => 'someValue'];

        $this->dataObjectHelperMock->expects($this->once())
            ->method('populateWithArray')
            ->with($entity, $data, AiContentQueueEntryInterface::class)
            ->willReturn($entity);

        $result = $this->hydrator->hydrate($entity, $data);

        $this->assertSame($entity, $result);
    }

    public function testExtract(): void
    {
        $entity = $this->createMock(AiContentQueueEntry::class);
        $data = ['someKey' => 'someValue'];

        $entity->expects($this->once())
            ->method('getData')
            ->willReturn($data);

        $result = $this->hydrator->extract($entity);

        $this->assertSame($data, $result);
    }
}
