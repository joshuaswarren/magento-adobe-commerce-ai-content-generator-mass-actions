<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Model\SearchCriteria;

use Creatuity\AIContentMassAction\Model\SearchCriteria\ProductCollectionProcessor;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zend_Db_Select;

class ProductCollectionProcessorTest extends TestCase
{
    private readonly ProductCollectionProcessor $processor;
    private readonly ProductCollection|MockObject $productCollectionMock;

    protected function setUp(): void
    {
        $collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->productCollectionMock = $this->getMockBuilder(ProductCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->productCollectionMock);

        $this->processor = new ProductCollectionProcessor($collectionFactoryMock);
    }

    public function testProcess(): void
    {
        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);

        $collectionMock = $this->createMock(AbstractDb::class);
        $selectMock = $this->createMock(Zend_Db_Select::class);
        $productSelectMock = $this->createMock(Zend_Db_Select::class);

        $this->productCollectionMock->expects($this->once())
            ->method('setStoreId')
            ->with(0)
            ->willReturnSelf();

        $this->productCollectionMock->expects($this->once())
            ->method('addAttributeToSelect')
            ->with(['name'])
            ->willReturnSelf();

        $this->productCollectionMock->expects($this->once())
            ->method('addFieldToFilter')
            ->with('name', ['notnull' => true])
            ->willReturnSelf();

        $this->productCollectionMock->expects($this->once())
            ->method('getSelect')
            ->willReturn($productSelectMock);

        $collectionMock->expects($this->once())
            ->method('getSelect')
            ->willReturn($selectMock);

        $selectMock->expects($this->once())
            ->method('join')
            ->with(['products' => $productSelectMock], 'main_table.product_id = products.entity_id', ['product_name' => 'name']);

        $this->processor->process($searchCriteriaMock, $collectionMock);
    }
}

