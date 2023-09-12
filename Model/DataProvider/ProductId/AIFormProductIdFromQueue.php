<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\DataProvider\ProductId;

use Creatuity\AIContent\Model\DataProvider\ProductId\AIFormProductIdProviderInterface;
use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Magento\Framework\App\RequestInterface;

class AIFormProductIdFromQueue implements AIFormProductIdProviderInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly GetNextAiContentEntry $getNextAiContentEntry
    ) {
    }

    public function get(): ?int
    {
        $prevEntryId = $this->request->getParam('prev_entry_id', null);
        $entry = $this->getNextAiContentEntry->execute((int)$prevEntryId);

        return $entry?->getProductId();
    }
}