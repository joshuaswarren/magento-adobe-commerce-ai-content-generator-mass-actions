<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Controller\Adminhtml\Queue;

use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class Clear extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        private readonly AiContentQueueEntryRepositoryInterface $aiContentQueueEntryRepository,
        private readonly RedirectFactory $redirectFactory,
        private readonly LoggerInterface $logger,
        private readonly ManagerInterface $manager
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        try {
            $this->aiContentQueueEntryRepository->clear();
            $this->manager->addSuccessMessage(__("Queue has been cleared"));
        } catch (\Throwable $t) {
            $msg = __('Error occurred while clearing the queue');
            $this->manager->addErrorMessage($msg);
            $this->logger->error($msg, ['exception' => $t]);

        }
        $redirect = $this->redirectFactory->create();
        $redirect->setPath('*/*/');

        return $redirect;
    }
}