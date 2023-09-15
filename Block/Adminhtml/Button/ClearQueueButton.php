<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Block\Adminhtml\Button;

use Creatuity\AIContentMassAction\Model\IsActiveAiContentEntriesQueue;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ClearQueueButton implements ButtonProviderInterface
{
    public function __construct(
        private readonly UrlInterface $url,
        private readonly IsActiveAiContentEntriesQueue $isActiveAiContentEntriesQueue
    ) {
    }

    public function getButtonData(): array
    {
        if (!$this->isActiveAiContentEntriesQueue->execute()) {
            return [];
        }

        return [
            'id' => 'delete',
            'label' => __('Clear'),
            'on_click' => "deleteConfirm('" .__('Are you sure you want to clear the queue?') ."', '"
                . $this->getUrl() . "', {data: {}})",
            'class' => 'delete',
            'sort_order' => 10
        ];
    }

    public function getUrl(): string
    {
        return $this->url->getUrl('*/*/clear');
    }
}