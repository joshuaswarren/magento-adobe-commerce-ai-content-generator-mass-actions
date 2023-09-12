<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Controller\Adminhtml\AiContentEntry;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Queue extends Action implements HttpGetActionInterface
{

    public function execute(): ResultInterface
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}