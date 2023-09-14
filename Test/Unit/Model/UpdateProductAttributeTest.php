<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model;

use Creatuity\AIContentMassAction\Model\UpdateProductAttribute;
use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateProductAttributeTest extends TestCase
{
    private UpdateProductAttribute $updateProductAttribute;
    private ProductAction|MockObject $actionMock;

    protected function setUp(): void
    {
        $this->actionMock = $this->createMock(ProductAction::class);
        $this->updateProductAttribute = new UpdateProductAttribute($this->actionMock, [
            'meta_title' => true,
            'description' => true,
        ]);
    }

    public function testExecuteThrowsExceptionForUnsupportedAttribute(): void
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Cannot update attribute unsupported_attribute');

        $this->updateProductAttribute->execute(1, 'unsupported_attribute', 'value');
    }

    public function testExecuteUpdatesSupportedAttribute(): void
    {
        $productId = 1;
        $attrCode = 'meta_title';
        $value = 'Updated title';

        $this->actionMock->expects($this->once())
            ->method('updateAttributes')
            ->with([$productId], [$attrCode => $value], 0);

        $this->updateProductAttribute->execute($productId, $attrCode, $value);
    }
}
