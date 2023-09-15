<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntrySearchResultInterface;
use Magento\Framework\Api\SearchResults;

class AiContentQueueEntrySearchResult extends SearchResults implements AiContentQueueEntrySearchResultInterface
{
}