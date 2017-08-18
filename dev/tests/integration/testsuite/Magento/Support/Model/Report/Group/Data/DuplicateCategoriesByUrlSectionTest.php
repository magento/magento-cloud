<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model\Report\Group\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Catalog\Api\Data\CategoryAttributeInterface;

/**
 * Test for \Magento\Support\Model\Report\Group\Data\DuplicateCategoriesByUrlSection
 */
class DuplicateCategoriesByUrlSectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DuplicateCategoriesByUrlSection
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->model = Bootstrap::getObjectManager()->create(DuplicateCategoriesByUrlSection::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     *
     * @return void
     */
    public function testGenerate()
    {
        $objectManager = Bootstrap::getObjectManager();

        /** @var MetadataPool $metadataPool */
        $metadataPool = $objectManager->get(MetadataPool::class);
        $metadata = $metadataPool->getMetadata(CategoryInterface::class);
        $linkField = $metadata->getLinkField();

        /** @var ResourceConnection $resource */
        $resource = $objectManager->get(ResourceConnection::class);
        /** @var AdapterInterface $adapter */
        $adapter = $resource->getConnection();

        /** @var CategoryRepositoryInterface $repository */
        $repository = $objectManager->get(CategoryRepositoryInterface::class);
        $category1 = $repository->get(3);
        $category2 = $repository->get(4);

        /** @var \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository */
        $attributeRepository = $objectManager->get(\Magento\Eav\Api\AttributeRepositoryInterface::class);
        $urlKeyAttribute = $attributeRepository->get(CategoryAttributeInterface::ENTITY_TYPE_CODE, 'url_key');

        $adapter->update(
            $resource->getTableName('catalog_category_entity_varchar'),
            ['value' => 'key_1'],
            [
                'store_id = ?' => 0,
                'attribute_id = ?' => $urlKeyAttribute->getAttributeId(),
                $linkField . ' IN (?)' => [$category1->getData($linkField), $category2->getData($linkField)]
            ]
        );

        $result = $this->model->generate();
        $this->assertEquals(
            [
                [
                    '3',
                    'key_1',
                    'Category 1',
                    'Admin {ID:0}',
                ],
                [
                    '4',
                    'key_1',
                    'Category 1.1',
                    'Admin {ID:0}',
                ]
            ],
            $result['Duplicate Categories By URL Key']['data']
        );
    }
}
