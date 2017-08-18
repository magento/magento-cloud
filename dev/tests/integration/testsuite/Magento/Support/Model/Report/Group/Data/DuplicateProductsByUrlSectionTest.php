<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model\Report\Group\Data;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test for \Magento\Support\Model\Report\Group\Data\DuplicateProductsByUrlSection
 */
class DuplicateProductsByUrlSectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DuplicateProductsByUrlSection
     */
    protected $model;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->model = Bootstrap::getObjectManager()->create(DuplicateProductsByUrlSection::class);
        $this->eavConfig = Bootstrap::getObjectManager()->get(\Magento\Eav\Model\Config::class);
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

        /** @var MetadataPool $metadataPool */
        $metadataPool = $objectManager->get(MetadataPool::class);
        $metadata = $metadataPool->getMetadata(ProductInterface::class);
        $linkField = $metadata->getLinkField();

        /** @var ResourceConnection $resource */
        $resource = $objectManager->get(ResourceConnection::class);
        /** @var AdapterInterface $adapter */
        $adapter = $resource->getConnection();

        /** @var ProductRepositoryInterface $repository */
        $repository = $objectManager->get(ProductRepositoryInterface::class);
        $product3 = $repository->get('simple-3');
        $product4 = $repository->get('simple-4');

        $sql = $adapter->select()->from(
            ['ea' => $resource->getTableName('eav_attribute')],
            ['attribute_id']
        )->where(
            'ea.attribute_code = ?',
            'url_key'
        )->where(
            'ea.entity_type_id = ?',
            $this->eavConfig->getEntityType(\Magento\Catalog\Model\Product::ENTITY)->getId()
        );
        $attributeId = (int)$adapter->fetchOne($sql);

        $adapter->update(
            $resource->getTableName('catalog_product_entity_varchar'),
            ['value' => 'key_1'],
            [
                'attribute_id = ?' => $attributeId,
                $linkField . ' IN (?)' => [$product3->getData($linkField), $product4->getData($linkField)]
            ]
        );

        $result = $this->model->generate();

        $this->assertEquals(
            [
                [
                    $product3->getId(),
                    'key_1',
                    'Simple Product Not Visible On Storefront',
                    'Main Website {ID:1}',
                    'All',
                ],
                [
                    $product4->getId(),
                    'key_1',
                    'Simple Product Three',
                    'Main Website {ID:1}',
                    'All',
                ],
            ],
            $result['Duplicate Products By URL Key']['data']
        );
    }
}
