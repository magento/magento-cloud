<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model\Report\Group\Data;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test for \Magento\Support\Model\Report\Group\Data\DuplicateProductsBySkuSection
 */
class DuplicateProductsBySkuSectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DuplicateProductsBySkuSection
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->model = Bootstrap::getObjectManager()->create(DuplicateProductsBySkuSection::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     *
     * @return void
     */
    public function testGenerate()
    {
        $objectManager = Bootstrap::getObjectManager();

        /** @var ResourceConnection $resource */
        $resource = $objectManager->get(ResourceConnection::class);
        /** @var AdapterInterface $adapter */
        $adapter = $resource->getConnection();

        /** @var ProductRepositoryInterface $repository */
        $repository = $objectManager->get(ProductRepositoryInterface::class);
        $product3 = $repository->get('simple-3');
        $product4 = $repository->get('simple-4');

        $adapter->update(
            $resource->getTableName('catalog_product_entity'),
            ['sku' => 'simple-3'],
            ['sku = ?' => 'simple-4']
        );

        $result = $this->model->generate();

        $this->assertEquals(
            [
                [
                    $product3->getId(),
                    'simple-3',
                    'Simple Product Not Visible On Storefront',
                ],
                [
                    $product4->getId(),
                    'simple-3',
                    'Simple Product Three',
                ],
            ],
            $result['Duplicate Products By SKU']['data']
        );
    }
}
