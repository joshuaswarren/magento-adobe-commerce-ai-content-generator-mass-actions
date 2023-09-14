<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\ViewModel;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Creatuity\AIContentMassAction\Model\IsActiveAiContentEntriesQueue;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AiContentEntryViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly GetNextAiContentEntry $getNextAiContentEntry,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly RequestInterface $request,
        private readonly IsActiveAiContentEntriesQueue $isActiveAiContentEntriesQueue
    ) {
    }

    public function isMassAction(): bool
    {
        return (bool) $this->request->getParam('mass_action');
    }

    public function getProduct(): ?ProductInterface
    {
        $entry = $this->getQueueEntry();

        if (!$entry) {
            return null;
        }

        return $this->productRepository->getById($entry->getProductId());
    }

    public function isQueueEmpty(): bool
    {
        return !$this->isActiveAiContentEntriesQueue->execute();
    }

    public function getQueueEntry(): ?AiContentQueueEntryInterface
    {
        return $this->getNextAiContentEntry->execute((int) $this->request->getParam('prev_entry_id'));
    }
}