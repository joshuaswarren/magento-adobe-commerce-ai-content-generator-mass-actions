<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Controller\Adminhtml\Product;

use Creatuity\AIContentMassAction\Controller\Adminhtml\Product\SaveProductContent;
use Creatuity\AIContentMassAction\Model\UpdateProductAttribute;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SaveProductContentTest extends TestCase
{
    private readonly SaveProductContent $saveProductContent;
    private readonly UpdateProductAttribute|MockObject $updateProductAttribute;
    private readonly RequestInterface|MockObject $request;
    private readonly Json|MockObject $jsonResult;
    private readonly LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $jsonFactory = $this->createMock(JsonFactory::class);
        $this->updateProductAttribute = $this->createMock(UpdateProductAttribute::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->jsonResult = $this->createMock(Json::class);

        $jsonFactory->expects($this->once())->method('create')->willReturn($this->jsonResult);

        $this->saveProductContent = new SaveProductContent(
            $jsonFactory,
            $this->request,
            $this->updateProductAttribute,
            $this->logger,
            $this->createMock(Context::class)
        );
    }

    public function testExecuteSuccess(): void
    {
        $this->request
            ->expects($this->exactly(3))
            ->method('getParam')
            ->willReturnOnConsecutiveCalls(1, 'attribute', 'content');
        $this->jsonResult->expects($this->once())
            ->method('setHttpResponseCode')
            ->with(200)
            ->willReturnSelf();
        $this->jsonResult->expects($this->once())
            ->method('setData')
            ->with(['message' => ''])
            ->willReturnSelf();

        $result = $this->saveProductContent->execute();
        $this->assertSame($this->jsonResult, $result);
    }

    public function testExecuteLocalizedException(): void
    {
        $this->request
            ->expects($this->exactly(3))
            ->method('getParam')
            ->willReturnOnConsecutiveCalls(1, 'attribute', 'content');

        $exceptionMessage = 'Localized error occurred';
        $this->updateProductAttribute->expects($this->once())
            ->method('execute')
            ->willThrowException(new LocalizedException(__($exceptionMessage)));
        $this->logger->expects($this->once())->method('error');
        $this->jsonResult->expects($this->once())
            ->method('setHttpResponseCode')
            ->with(400)
            ->willReturnSelf();
        $this->jsonResult->expects($this->once())
            ->method('setData')
            ->with(['message' => $exceptionMessage])
            ->willReturnSelf();

        $result = $this->saveProductContent->execute();
        $this->assertSame($this->jsonResult, $result);
    }

    public function testExecuteUnknownException(): void
    {
        $this->request
            ->expects($this->exactly(3))
            ->method('getParam')
            ->willReturnOnConsecutiveCalls(1, 'attribute', 'content');
        $this->updateProductAttribute->expects($this->once())
            ->method('execute')
            ->willThrowException(new \RuntimeException());
        $this->logger->expects($this->once())->method('error');
        $this->jsonResult->expects($this->once())
            ->method('setHttpResponseCode')
            ->with(400)
            ->willReturnSelf();
        $this->jsonResult->expects($this->once())
            ->method('setData')
            ->with(['message' => (string) __('Unknown error occurred')])
            ->willReturnSelf();

        $result = $this->saveProductContent->execute();
        $this->assertSame($this->jsonResult, $result);
    }
}
