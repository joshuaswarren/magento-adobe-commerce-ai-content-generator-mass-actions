<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model\DataProvider\ContentType;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\DataProvider\ContentType\AIFormContentTypeFromQueue;
use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Magento\Framework\App\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AIFormContentTypeFromQueueTest extends TestCase
{
    private readonly AIFormContentTypeFromQueue $contentTypeProvider;
    private readonly RequestInterface|MockObject $request;
    private readonly GetNextAiContentEntry|MockObject $getNextAiContentEntry;

    protected function setUp(): void
    {
        $this->request = $this->createMock(RequestInterface::class);
        $this->getNextAiContentEntry = $this->createMock(GetNextAiContentEntry::class);

        $this->contentTypeProvider = new AIFormContentTypeFromQueue(
            $this->request,
            $this->getNextAiContentEntry
        );
    }

    public function testGetWithPrevEntryIdValidEntry(): void
    {
        $prevEntryId = 123;
        $entryMock = $this->createMock(AiContentQueueEntryInterface::class);
        $entryMock->expects($this->once())->method('getContentType')->willReturn('some-content-type');

        $this->request->expects($this->once())
            ->method('getParam')
            ->with('prev_entry_id', null)
            ->willReturn($prevEntryId);
        $this->getNextAiContentEntry->expects($this->once())
            ->method('execute')
            ->with($prevEntryId)
            ->willReturn($entryMock);

        $this->assertEquals('some-content-type', $this->contentTypeProvider->get());
    }

    public function testGetWithPrevEntryIdNoEntry(): void
    {
        $prevEntryId = 123;

        $this->request->expects($this->once())
            ->method('getParam')
            ->with('prev_entry_id', null)
            ->willReturn($prevEntryId);
        $this->getNextAiContentEntry->expects($this->once())
            ->method('execute')
            ->with($prevEntryId)
            ->willReturn(null);

        $this->assertNull($this->contentTypeProvider->get());
    }

    public function testGetWithoutPrevEntryIdValidEntry(): void
    {
        $entryMock = $this->createMock(AiContentQueueEntryInterface::class);
        $entryMock->expects($this->once())->method('getContentType')->willReturn('some-content-type');

        $this->request->expects($this->once())
            ->method('getParam')
            ->with('prev_entry_id', null)
            ->willReturn(null);
        $this->getNextAiContentEntry->expects($this->once())
            ->method('execute')
            ->with(0)
            ->willReturn($entryMock);

        $this->assertEquals('some-content-type', $this->contentTypeProvider->get());
    }
}