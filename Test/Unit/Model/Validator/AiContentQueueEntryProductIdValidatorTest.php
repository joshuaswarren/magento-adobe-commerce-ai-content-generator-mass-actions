<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model\Validator;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\Validator\AiContentQueueEntryProductIdValidator;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AiContentQueueEntryProductIdValidatorTest extends TestCase
{
    private readonly AiContentQueueEntryProductIdValidator $validator;
    private readonly ValidationResultFactory|MockObject $validationResultFactory;
    private readonly Collection|MockObject $productCollection;

    protected function setUp(): void
    {
        $productCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->validationResultFactory = $this->createMock(ValidationResultFactory::class);
        $this->productCollection = $this->createMock(Collection::class);

        $productCollectionFactory->expects($this->once())
            ->method('create')->willReturn($this->productCollection);

        $this->validator = new AiContentQueueEntryProductIdValidator(
            $productCollectionFactory,
            $this->validationResultFactory
        );
    }

    public function testValidateWithExistingProduct(): void
    {
        $entry = $this->createEntryWithProductId(123);

        $this->productCollection->expects($this->once())->method('getSize')->willReturn(1);

        $this->validationResultFactory->expects($this->once())
            ->method('create')
            ->with(['errors' => []])
            ->willReturn($this->createMock(ValidationResult::class));

        $this->validator->validate($entry);
    }

    public function testValidateWithNonExistingProduct(): void
    {
        $entry = $this->createEntryWithProductId(456);

        $this->productCollection->expects($this->once())->method('getSize')->willReturn(0);

        $this->validationResultFactory->expects($this->once())
            ->method('create')
            ->with(['errors' => [__('Product ID %1 doesn\'t exist', 456)]])
            ->willReturn($this->createMock(ValidationResult::class));

        $this->validator->validate($entry);
    }

    private function createEntryWithProductId(int $productId): AiContentQueueEntryInterface
    {
        $entry = $this->createMock(AiContentQueueEntryInterface::class);
        $entry->expects($this->atLeast(1))->method('getProductId')->willReturn($productId);

        return $entry;
    }
}
