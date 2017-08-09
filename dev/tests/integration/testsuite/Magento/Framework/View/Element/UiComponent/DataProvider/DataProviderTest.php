<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\View\Element\UiComponent\DataProvider;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Request;
use Magento\Staging\Model\VersionManager;

/**
 * Tests Magento\Framework\View\Element\UiComponent\DataProvider Class.
 */
class DataProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting
     */
    private $reporting;

    /**
     * @var \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\TestFramework\Request
     */
    private $request;

    /**
     * @var \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
     */
    private $dataProvider;
    
    public function setUp()
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->request = $objectManager->get(Request::class);
        $this->request->setParams(['id' => '1']);
        $versionManager = $objectManager->get(VersionManager::class);
        $versionManager->setCurrentVersionId(101);
        $this->collectionFactory = $objectManager->create(CollectionFactory::class);

        $this->reporting = $objectManager->create(
            Reporting::class,
            [
                'collectionFactory' => $this->collectionFactory,
                'request' => $this->request
            ]
        );
        $this->dataProvider = $objectManager->create(
            DataProvider::class,
            [
                'name' => 'catalogstaging_upcoming_grid_data_source',
                'primaryFieldName' => 'row_id',
                'requestFieldName' => 'id',
            ]
        );
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoAppArea adminhtml
     * @magentoDataFixture Magento/Checkout/_files/simple_product.php
     * @magentoDataFixture Magento/Staging/_files/staging_catalog_product_entity.php
     * @magentoDataFixture Magento/Staging/_files/staging_update.php
     */
    public function testSearch()
    {
        $result = $this->dataProvider->getSearchResult();
        $this->assertEquals(4, count($result->getItems()));
    }
}
