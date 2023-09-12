<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\ViewModel;

use Creatuity\AIContent\Model\DataProvider\ProductId\AIFormProductIdProviderInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class AiContentEntryViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly AIFormProductIdProviderInterface $productIdProvider,
        private readonly GetNextAiContentEntry $getNextAiContentEntry,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly RequestInterface $request
    ) {
    }

    public function getProduct(): ?ProductInterface
    {
        $entry = $this->getQueueEntry();

        if (!$entry) {
            return null;
        }

        return $this->productRepository->getById($entry->getProductId());
    }

    public function getQueueEntry(): ?AiContentQueueEntryInterface
    {
        return $this->getNextAiContentEntry->execute((int) $this->request->getParam('prev_entry_id'));
    }
}