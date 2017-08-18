<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Model\Report\Group\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Test for \Magento\Support\Model\Report\Group\Data\CorruptedCategoriesDataSection
 *
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 */
class CorruptedCategoriesDataSectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CorruptedCategoriesDataSection
     */
    protected $model;

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Bootstrap::getInstance()->getBootstrap()
            ->getApplication()
            ->getDbInstance()
            ->restoreFromDbDump();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();

        /**
         * After installation system has two categories: root one with ID:1 and Default category with ID:2
         */
        /** @var $category Category */
        $category = $objectManager->create(Category::class);
        $category->isObjectNew(true);
        $category->setId(3)
            ->setName('Category 1')
            ->setParentId(2)
            ->setPath('1/2/3')
            ->setLevel(2)
            ->setAvailableSortBy('name')
            ->setDefaultSortBy('name')
            ->setIsActive(true)
            ->setPosition(1)
            ->save();

        $category = $objectManager->create(Category::class);
        $category->isObjectNew(true);
        $category->setId(4)
            ->setName('Category 1.1')
            ->setParentId(3)
            ->setPath('1/2/3/4')
            ->setLevel(3)
            ->setAvailableSortBy('name')
            ->setDefaultSortBy('name')
            ->setIsActive(true)
            ->setIsAnchor(true)
            ->setPosition(1)
            ->save();

        $this->model = Bootstrap::getObjectManager()->create(CorruptedCategoriesDataSection::class);
    }

    /**
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

        $adapter->update(
            $resource->getTableName('catalog_category_entity'),
            ['level' => 10],
            [$linkField . ' = ?' => [$category1->getData($linkField)]]
        );

        $adapter->update(
            $resource->getTableName('catalog_category_entity'),
            ['children_count' => 20],
            [$linkField . ' = ?' => [$category2->getData($linkField)]]
        );

        $result = $this->model->generate();
        $this->assertEquals(
            [
                [
                    3, // id
                    1, // Expected Children Count
                    '1', // Actual Children Count
                    2, // Expected Level
                    '10 (diff: +8)', // Actual Level
                ],
                [
                    4, // id
                    0, // Expected Children Count
                    '20 (diff: +20)', // Actual Children Count
                    3, // Expected Level
                    '3', // Actual Level
                ],
            ],
            $result['Corrupted Categories Data']['data']
        );
    }
}
