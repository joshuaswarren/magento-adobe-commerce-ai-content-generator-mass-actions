<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\SearchCriteria;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb;

class ProductCollectionProcessor implements CollectionProcessorInterface
{
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory
    ) {
    }

    public function process(SearchCriteriaInterface $searchCriteria, AbstractDb $collection): void
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->setStoreId(0);
        $productCollection->addAttributeToSelect(['name']);
        $productCollection->addFieldToFilter('name', ['notnull' => true]);
        $select = $productCollection->getSelect();

        $collection->getSelect()->join(
            ['products' => $select],
            'main_table.product_id = products.entity_id',
            ['product_name' => 'name']
        );
    }
}