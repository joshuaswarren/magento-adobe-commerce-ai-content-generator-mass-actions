<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model\Processor;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\CreateAiContentQueueEntry;
use Creatuity\AIContentMassAction\Model\Processor\AiContentQueueEntrySaveProcessor;
use Creatuity\AIContentMassAction\Model\Validator\AiContentQueueEntryValidatorInterface;
use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\Validation\ValidationResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AiContentQueueEntrySaveProcessorTest extends TestCase
{
    private AiContentQueueEntrySaveProcessor $processor;
    private MockObject $validator;
    private MockObject $entryCreator;
    private MockObject $repository;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(AiContentQueueEntryValidatorInterface::class);
        $this->entryCreator = $this->createMock(CreateAiContentQueueEntry::class);
        $this->repository = $this->createMock(AiContentQueueEntryRepositoryInterface::class);

        $this->processor = new AiContentQueueEntrySaveProcessor(
            $this->validator,
            $this->entryCreator,
            $this->repository
        );
    }

    public function testProcessHappyPath(): void
    {
        $params = [
            'product_id' => 1,
            'content_type' => 'type'
        ];
        $entry = $this->createMock(AiContentQueueEntryInterface::class);

        $this->entryCreator->expects($this->once())
            ->method('create')
            ->with([
                AiContentQueueEntryInterface::PRODUCT_ID => $params['product_id'],
                AiContentQueueEntryInterface::CONTENT_TYPE => $params['content_type']
            ])
            ->willReturn($entry);

        $validationResult = $this->createMock(ValidationResult::class);
        $validationResult->expects($this->once())->method('isValid')->willReturn(true);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($entry)
            ->willReturn($validationResult);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($entry);

        $this->assertSame($entry, $this->processor->process($params));
    }

    public function testProcessValidationError(): void
    {
        $this->expectException(ValidationException::class);

        $params = [
            'product_id' => 1,
            'content_type' => 'type'
        ];
        $entry = $this->createMock(AiContentQueueEntryInterface::class);

        $this->entryCreator->expects($this->once())
            ->method('create')
            ->with([
                AiContentQueueEntryInterface::PRODUCT_ID => $params['product_id'],
                AiContentQueueEntryInterface::CONTENT_TYPE => $params['content_type']
            ])
            ->willReturn($entry);

        $validationResult = $this->createMock(ValidationResult::class);
        $validationResult->expects($this->once())->method('isValid')->willReturn(false);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($entry)
            ->willReturn($validationResult);

        $this->repository->expects($this->never())->method('save');

        $this->processor->process($params);
    }
}