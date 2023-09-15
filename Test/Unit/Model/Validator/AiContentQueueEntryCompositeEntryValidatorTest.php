<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model\Validator;

use Creatuity\AIContentMassAction\Model\Validator\AiContentQueueEntryCompositeEntryValidator;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\Validator\AiContentQueueEntryValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use PHPUnit\Framework\TestCase;

class AiContentQueueEntryCompositeEntryValidatorTest extends TestCase
{
    private readonly AiContentQueueEntryCompositeEntryValidator $compositeValidator;
    private readonly ValidationResultFactory $validationResultFactory;
    private readonly array $validators;

    protected function setUp(): void
    {
        $this->validationResultFactory = $this->createMock(ValidationResultFactory::class);
        $this->validators = [
            $this->createMock(AiContentQueueEntryValidatorInterface::class),
            $this->createMock(AiContentQueueEntryValidatorInterface::class)
        ];

        $this->compositeValidator = new AiContentQueueEntryCompositeEntryValidator(
            $this->validationResultFactory,
            $this->validators
        );
    }

    public function testValidate(): void
    {
        $queueEntry = $this->createMock(AiContentQueueEntryInterface::class);
        $errorLists = [['error1', 'error2'], ['error3']];

        foreach ($this->validators as $index => $validator) {
            $validationResult = $this->createMock(ValidationResult::class);
            $validationResult->expects($this->once())
                ->method('getErrors')
                ->willReturn($errorLists[$index]);
            $validator->expects($this->once())
                ->method('validate')
                ->with($queueEntry)
                ->willReturn($validationResult);
        }

        $this->validationResultFactory->expects($this->once())
            ->method('create')
            ->with(['errors' => array_merge($errorLists[0], $errorLists[1])])
            ->willReturn($this->createMock(ValidationResult::class));

        $result = $this->compositeValidator->validate($queueEntry);
        $this->assertInstanceOf(ValidationResult::class, $result);
    }

    public function testConstructorThrowsExceptionForInvalidValidator(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new AiContentQueueEntryCompositeEntryValidator(
            $this->validationResultFactory,
            [$this->createMock(\stdClass::class)]
        ))->validate($this->createMock(AiContentQueueEntryInterface::class));
    }
}

