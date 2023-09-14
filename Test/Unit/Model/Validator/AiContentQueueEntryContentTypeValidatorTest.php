<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model\Validator;

use Creatuity\AIContent\Enum\AiContentTypeEnum;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\Validator\AiContentQueueEntryContentTypeValidator;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AiContentQueueEntryContentTypeValidatorTest extends TestCase
{
    private readonly AiContentQueueEntryContentTypeValidator $validator;
    private readonly ValidationResultFactory|MockObject $validationResultFactory;

    protected function setUp(): void
    {
        $this->validationResultFactory = $this->createMock(ValidationResultFactory::class);

        $this->validator = new AiContentQueueEntryContentTypeValidator(
            $this->validationResultFactory
        );
    }

    public function testValidateSupportsDescriptionGroup(): void
    {
        $entry = $this->createEntryWithContentType(AiContentTypeEnum::DESCRIPTION_GROUP->value);

        $this->validationResultFactory->expects($this->once())
            ->method('create')
            ->with(['errors' => []])
            ->willReturn($this->createMock(ValidationResult::class));

        $this->validator->validate($entry);
    }

    public function testValidateSupportsMetaGroup(): void
    {
        $entry = $this->createEntryWithContentType(AiContentTypeEnum::META_GROUP->value);

        $this->validationResultFactory->expects($this->once())
            ->method('create')
            ->with(['errors' => []])
            ->willReturn($this->createMock(ValidationResult::class));

        $this->validator->validate($entry);
    }

    public function testValidateRejectsUnsupportedContentType(): void
    {
        $unsupportedType = 'UNSUPPORTED_TYPE';
        $entry = $this->createEntryWithContentType($unsupportedType);

        $this->validationResultFactory->expects($this->once())
            ->method('create')
            ->with(['errors' => [__('Unsupported content type %1', $unsupportedType)]])
            ->willReturn($this->createMock(ValidationResult::class));

        $this->validator->validate($entry);
    }

    private function createEntryWithContentType(string $contentType): AiContentQueueEntryInterface
    {
        $entry = $this->createMock(AiContentQueueEntryInterface::class);
        $entry->expects($this->atLeast(1))->method('getContentType')->willReturn($contentType);

        return $entry;
    }
}
