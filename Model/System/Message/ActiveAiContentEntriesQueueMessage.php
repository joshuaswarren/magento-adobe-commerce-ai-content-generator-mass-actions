<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\System\Message;

use Creatuity\AIContentMassAction\Model\IsActiveAiContentEntriesQueue;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\UrlInterface;

class ActiveAiContentEntriesQueueMessage implements MessageInterface
{
    public function __construct(
        private readonly UrlInterface $url,
        private readonly IsActiveAiContentEntriesQueue $activeAiContentEntriesQueue
    ) {
    }

    public const MESSAGE_IDENTITY = 'active_ai_content_entries_queue';

    public function getIdentity(): string
    {
        return self::MESSAGE_IDENTITY;
    }

    public function isDisplayed(): bool
    {
        return $this->activeAiContentEntriesQueue->execute();
    }

    public function getText(): string
    {
        $url = $this->url->getUrl('creatuityAiContent/queue');

        return (string) __('There are products awaiting content generation with AI.')
            . ' <a href="' . $url . '" target="_blank">' . __('Go to queue.') . '</a>';
    }

    public function getSeverity(): int
    {
        return self::SEVERITY_MINOR;
    }
}