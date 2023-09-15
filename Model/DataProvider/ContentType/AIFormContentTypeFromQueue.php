<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\DataProvider\ContentType;

use Creatuity\AIContent\Model\DataProvider\ContentType\AIFormContentTypeProviderInterface;
use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Magento\Framework\App\RequestInterface;

class AIFormContentTypeFromQueue implements AIFormContentTypeProviderInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly GetNextAiContentEntry $getNextAiContentEntry
    ) {
    }

    public function get(): ?string
    {
        $prevEntryId = $this->request->getParam('prev_entry_id', null);

        $entry = $this->getNextAiContentEntry->execute((int)$prevEntryId);

        return $entry?->getContentType();
    }
}