<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Controller\Adminhtml\Product;

use Creatuity\AIContentMassAction\Model\UpdateProductAttribute;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class SaveProductContent extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Catalog::update_attributes';

    public function __construct(
        private readonly JsonFactory $jsonFactory,
        private readonly RequestInterface $request,
        private readonly UpdateProductAttribute $updateProductAttribute,
        private readonly LoggerInterface $logger,
        Context $context
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $code = 400;
        $msg = '';
        try {
            $this->updateProductAttribute->execute(
                (int)$this->request->getParam('product_id'),
                (string)$this->request->getParam('attribute'),
                $this->request->getParam('content')
            );
            $code = 200;
        } catch (LocalizedException $e) {
            $msg = $e->getMessage();
            $this->logger->error($e->getMessage(), ['exception' => $e, 'params' => $this->request->getParams()]);
        } catch (\Throwable $e) {
            $msg = (string) __('Unknown error occurred');
            $this->logger->error($e->getMessage(), ['exception' => $e, 'params' => $this->request->getParams()]);
        }

        return $this->jsonFactory->create()->setHttpResponseCode($code)->setData(['message' => $msg]);
    }
}