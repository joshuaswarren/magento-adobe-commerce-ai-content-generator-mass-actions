<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Block\Adminhtml\Button;

use Creatuity\AIContentMassAction\Model\IsActiveAiContentEntriesQueue;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class GenerateOneByOneButton implements ButtonProviderInterface
{
    public function __construct(
        private readonly IsActiveAiContentEntriesQueue $isActiveAiContentEntriesQueue
    ) {
    }

    public function getButtonData(): array
    {
        if (!$this->isActiveAiContentEntriesQueue->execute()) {
            return [];
        }

        return [
            'label' => __('Generate One By One'),
            'class' => 'action-primary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'creatuityaicontent_queue_index.creatuityaicontent_queue_index.general.modal_container.generate_modal.general.creatuityaicontent_generate_form',
                                'actionName' => 'destroyInserted'
                            ],
                            [
                                'targetName' => 'creatuityaicontent_queue_index.creatuityaicontent_queue_index.general.modal_container.generate_modal',
                                'actionName' => 'openModal'
                            ],
                            [
                                'targetName' => 'creatuityaicontent_queue_index.creatuityaicontent_queue_index.general.modal_container.generate_modal.general.creatuityaicontent_generate_form',
                                'actionName' => 'render',
                                'params' => [
                                    ['mass_action' => 1]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'on_click' => '',
            'sort_order' => 20
        ];
    }
}