<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterface;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntryRepository;
use Creatuity\AIContentMassAction\Model\DeleteQueueEntry;
use Creatuity\AIContentMassAction\Model\GetQueueEntryList;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntry;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry as AiContentQueueEntryResource;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AiContentQueueEntryRepositoryTest extends TestCase
{
    private AiContentQueueEntryRepository $repository;
    private AiContentQueueEntry|MockObject $aiContentQueueEntryResourceMock;
    private AdapterInterface|MockObject $adapterMock;
    private readonly GetQueueEntryList|MockObject $getQueueEntryListMock;
    private readonly DeleteQueueEntry|MockObject $deleteQueueEntryMock;

    protected function setUp(): void
    {
        $this->aiContentQueueEntryResourceMock = $this->createMock(AiContentQueueEntryResource::class);
        $this->getQueueEntryListMock = $this->createMock(GetQueueEntryList::class);
        $this->deleteQueueEntryMock = $this->createMock(DeleteQueueEntry::class);
        $this->adapterMock = $this->createMock(AdapterInterface::class);

        $this->repository = new AiContentQueueEntryRepository(
            $this->aiContentQueueEntryResourceMock,
            $this->getQueueEntryListMock,
            $this->deleteQueueEntryMock
        );
    }

    public function testSave(): void
    {
        $queueEntry = $this->createMock(AiContentQueueEntry::class);
        $this->aiContentQueueEntryResourceMock->expects($this->once())
            ->method('save')
            ->with($queueEntry);

        $this->repository->save($queueEntry);
    }

    public function testSaveThrowsException(): void
    {
        $this->expectException(CouldNotSaveException::class);

        $queueEntry = $this->createMock(AiContentQueueEntry::class);
        $this->aiContentQueueEntryResourceMock->expects($this->once())->method('save')
            ->willThrowException(new \Exception());

        $this->repository->save($queueEntry);
    }

    public function testGetList(): void
    {
        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);
        $searchResultMock = $this->createMock(AiContentQueueEntrySearchResultInterface::class);

        $this->getQueueEntryListMock->expects($this->once())->method('execute')->willReturn($searchResultMock);
        $this->assertSame($searchResultMock, $this->repository->getList($searchCriteriaMock));
    }

    public function testDeleteById(): void
    {
        $entryId = 123;
        $this->deleteQueueEntryMock->expects($this->once())->method('execute')->with($entryId);
        $this->repository->deleteById($entryId);
    }

    public function testDeleteByIdThrowsException(): void
    {
        $this->expectException(CouldNotDeleteException::class);
        $entryId = 123;
        $this->deleteQueueEntryMock->expects($this->once())
            ->method('execute')->with($entryId)
            ->willThrowException(new \Exception());
        $this->repository->deleteById($entryId);
    }

    public function testClear(): void
    {
        $this->aiContentQueueEntryResourceMock
            ->expects($this->once())->method('getConnection')
            ->willReturn($this->adapterMock);
        $this->adapterMock->expects($this->once())
            ->method('truncateTable')
            ->with(AiContentQueueEntryResource::TABLE_NAME);

        $this->repository->clear();
    }
}
