<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Block\Adminhtml\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;

class GenerateOneByOneButton extends Generic
{
    public function getButtonData()
    {
        return [
            'label' => __('Generate One By One'),
            'class' => 'action-primary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'creatuityaicontent_aicontententry_queue.creatuityaicontent_aicontententry_queue.general.modal_container.generate_modal.general.creatuityaicontent_generate_form',
                                'actionName' => 'destroyInserted'
                            ],
                            [
                                'targetName' => 'creatuityaicontent_aicontententry_queue.creatuityaicontent_aicontententry_queue.general.modal_container.generate_modal',
                                'actionName' => 'openModal'
                            ],
                            [
                                'targetName' => 'creatuityaicontent_aicontententry_queue.creatuityaicontent_aicontententry_queue.general.modal_container.generate_modal.general.creatuityaicontent_generate_form',
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