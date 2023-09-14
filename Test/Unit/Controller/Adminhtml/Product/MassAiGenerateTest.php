<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Controller\Adminhtml\Product;

use Creatuity\AIContentMassAction\Controller\Adminhtml\Product\MassAiGenerate;
use Creatuity\AIContentMassAction\Model\Processor\AiContentQueueEntrySaveProcessor;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Magento\Ui\Component\MassAction\Filter;

class MassAiGenerateTest extends TestCase
{
    private readonly MassAiGenerate $massAiGenerate;
    private readonly AiContentQueueEntrySaveProcessor|MockObject $aiContentQueueEntrySaveProcessor;
    private readonly RequestInterface|MockObject $request;
    private readonly Redirect|MockObject $redirect;
    private readonly Filter|MockObject $filter;
    private readonly Collection|MockObject $productCollection;

    protected function setUp(): void
    {
        $this->aiContentQueueEntrySaveProcessor = $this->createMock(AiContentQueueEntrySaveProcessor::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->redirect = $this->createMock(Redirect::class);
        $this->redirect->expects($this->once())->method('setPath')->with('*/queue')->willReturnSelf();
        $redirectFactory = $this->createMock(RedirectFactory::class);
        $redirectFactory->expects($this->once())->method('create')->willReturn($this->redirect);
        $this->filter = $this->createMock(Filter::class);
        $productCollectionFactory = $this->createMock(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class);
        $this->productCollection = $this->createMock(Collection::class);
        $productCollectionFactory->expects($this->once())->method('create')->willReturn($this->productCollection);
        $this->context = $this->createMock(Context::class);

        $this->massAiGenerate = new MassAiGenerate(
            $this->createMock(Context::class),
            $this->aiContentQueueEntrySaveProcessor,
            $this->request,
            $redirectFactory,
            $this->createMock(ManagerInterface::class),
            $this->createMock(LoggerInterface::class),
            $this->filter,
            $productCollectionFactory
        );
    }

    public function testExecute(): void
    {
        $productIds = [1, 2, 3];
        $contentType = 'someType';

        $this->filter->expects($this->once())->method('getCollection')->willReturn($this->productCollection);
        $this->productCollection->expects($this->once())->method('getAllIds')->willReturn($productIds);
        $this->request->expects($this->any())->method('getParam')->with('content-type')->willReturn($contentType);
        $this->aiContentQueueEntrySaveProcessor->expects($this->exactly(count($productIds)))->method('process');
        $result = $this->massAiGenerate->execute();
        $this->assertSame($this->redirect, $result);
    }
}
