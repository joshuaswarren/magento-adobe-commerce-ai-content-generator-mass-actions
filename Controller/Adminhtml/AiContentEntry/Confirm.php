<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Controller\Adminhtml\AiContentEntry;

use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Psr\Log\LoggerInterface;

class Confirm extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly AiContentQueueEntryRepositoryInterface $aiContentQueueEntryRepository,
        private readonly RequestInterface $request,
        private readonly JsonFactory $jsonFactory,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $json = $this->jsonFactory->create();
        try {
            $this->aiContentQueueEntryRepository->deleteById((int)$this->request->getParam('entry_id'));
            $data = [
                'success' => true,
            ];
        } catch (CouldNotDeleteException $e) {
            $data = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            $this->logger->error($e->getMessage(), ['exception' => $e, 'params' => $this->request->getParams()]);
            $json->setHttpResponseCode(400);
        } catch (\Throwable $t) {
            $data = [
                'success' => false,
                'message' => 'Unknown error occurred'
            ];
            $this->logger->error(
                'Unknown error occurred in ' . self::class,
                ['exception' => $t, 'params' => $this->request->getParams()]
            );
            $json->setHttpResponseCode(400);
        }

        $json->setData($data);

        return $json;
    }
}