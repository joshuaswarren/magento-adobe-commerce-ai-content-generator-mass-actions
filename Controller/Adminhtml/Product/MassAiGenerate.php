<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Controller\Adminhtml\Product;

use Creatuity\AIContentMassAction\Model\Processor\AiContentQueueEntrySaveProcessor;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Validation\ValidationException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

class MassAiGenerate extends Action implements HttpPostActionInterface
{

    public function __construct(
        Context $context,
        private readonly AiContentQueueEntrySaveProcessor $aiContentQueueEntrySaveProcessor,
        private readonly RequestInterface $request,
        private readonly RedirectFactory $redirectFactory,
        private readonly ManagerInterface $messanger,
        private readonly LoggerInterface $logger,
        private readonly Filter $filter,
        private readonly CollectionFactory $productCollectionFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $productIds = $this->filter->getCollection($this->productCollectionFactory->create())->getAllIds();
        $contentType = $this->request->getParam('content-type');

        foreach ($productIds as $productId) {
            try {
                $this->aiContentQueueEntrySaveProcessor->process([
                    'product_id' => (int)$productId,
                    'content_type' => (string)$contentType
                ]);
            } catch (ValidationException $e) {
                foreach ($e->getErrors() as $error) {
                    $this->messanger->addErrorMessage($error->getMessage());
                }
            } catch (\Throwable $t) {
                $msg =   (string) __(
                    'Error while processing product %1 (content type: %2)',
                    $productId,
                    $contentType
                );
                $this->logger->error($msg, ['exception' => $t, 'params' => $this->request->getParams()]);
                $this->messanger->addErrorMessage($msg);
            }
        }

        return $this->redirectFactory->create()->setPath('*/queue');
    }
}