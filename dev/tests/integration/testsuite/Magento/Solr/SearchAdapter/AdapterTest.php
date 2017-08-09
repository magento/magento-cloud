<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Solr\SearchAdapter;

use Magento\Solr\Helper\Data;

/**
 * Class AdapterTest.
 *
 * @magentoDbIsolation disabled
 * @magentoAppIsolation enabled
 * @magentoDataFixture Magento/Framework/Search/_files/products.php
 */
class AdapterTest extends \Magento\Framework\Search\Adapter\Mysql\AdapterTest
{
    /**
     * @var string
     */
    protected $searchEngine = Data::SOLR;

    /**
     * @return string
     */
    protected function getRequestConfig()
    {
        return __DIR__ . '/_files/requests.xml';
    }

    /**
     * Get request config path.
     *
     * @return string
     */
    protected function getRequestConfigPath()
    {
        return __DIR__ . '/_files/requests.xml';
    }
    
    /**
     * @return \Magento\Framework\Search\AdapterInterface
     */
    protected function createAdapter()
    {
        return $this->objectManager->create('Magento\Solr\SearchAdapter\Adapter');
    }

    /**
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testMatchQuery()
    {
        parent::testMatchQuery();
    }

    /**
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testAggregationsQuery()
    {
        $this->markTestSkipped('Range query does not implemented.');
    }

    /**
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testMatchQueryFilters()
    {
        parent::testMatchQueryFilters();
    }

    /**
     * Range filter test with all fields filled.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testRangeFilterWithAllFields()
    {
        parent::testRangeFilterWithAllFields();
    }

    /**
     * Range filter test without from field filled.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testRangeFilterWithoutFromField()
    {
        parent::testRangeFilterWithoutFromField();
    }

    /**
     * Range filter test without to field filled.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testRangeFilterWithoutToField()
    {
        parent::testRangeFilterWithoutToField();
    }

    /**
     * Term filter test.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testTermFilter()
    {
        parent::testTermFilter();
    }

    /**
     * Term filter test.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testTermFilterArray()
    {
        parent::testTermFilterArray();
    }

    /**
     * Wildcard filter test.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testWildcardFilter()
    {
        parent::testWildcardFilter();
    }

    /**
     * Request limits test.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testSearchLimit()
    {
        parent::testSearchLimit();
    }

    /**
     * Bool filter test.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testBoolFilter()
    {
        parent::testBoolFilter();
    }

    /**
     * Test bool filter with nested negative bool filter.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testBoolFilterWithNestedNegativeBoolFilter()
    {
        parent::testBoolFilterWithNestedNegativeBoolFilter();
    }

    /**
     * Test range inside nested negative bool filter.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testBoolFilterWithNestedRangeInNegativeBoolFilter()
    {
        parent::testBoolFilterWithNestedRangeInNegativeBoolFilter();
    }

    /**
     * Sample Advanced search request test.
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @dataProvider advancedSearchDataProvider
     * @return void
     */
    public function testSimpleAdvancedSearch(
        $nameQuery,
        $descriptionQuery,
        $rangeFilter,
        $expectedRecordsCount
    ) {
        parent::testSimpleAdvancedSearch(
            $nameQuery,
            $descriptionQuery,
            $rangeFilter,
            $expectedRecordsCount
        );
    }

    /**
     * @magentoDataFixture Magento/Framework/Search/_files/filterable_attribute.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @return void
     */
    public function testCustomFilterableAttribute()
    {
        parent::testCustomFilterableAttribute();
    }

    /**
     * Advanced search request using date product attribute.
     *
     * @param $rangeFilter
     * @param $expectedRecordsCount
     * @return void
     * @magentoDataFixture Magento/Framework/Search/_files/date_attribute.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @dataProvider dateDataProvider
     */
    public function testAdvancedSearchDateField($rangeFilter, $expectedRecordsCount)
    {
        parent::testAdvancedSearchDateField($rangeFilter, $expectedRecordsCount);
    }

    /**
     * @magentoDataFixture Magento/Framework/Search/_files/product_configurable.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @dataProvider dateDataProvider
     * @return void
     */
    public function testAdvancedSearchConfigProductWithOutOfStockOption()
    {
        $this->markTestSkipped('Filter of composite products with Out of Stock child not supported till MAGETWO-56525');
        parent::testAdvancedSearchConfigProductWithOutOfStockOption();
    }

    /**
     * Search request using custom price attribute.
     *
     * @param $rangeFilter
     * @param $expectedRecordsCount
     * @return void
     * @magentoDataFixture Magento/Framework/Search/_files/price_attribute.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @dataProvider priceDataProvider
     */
    public function testSearchCustomPriceField($rangeFilter, $expectedRecordsCount)
    {
        parent::testSearchCustomPriceField($rangeFilter, $expectedRecordsCount);
    }

    /**
     * Filter by tax class.
     *
     * @magentoDataFixture Magento/Framework/Search/_files/grouped_product.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     *
     * @return void
     */
    public function testFilterByTaxClass()
    {
        parent::testFilterByTaxClass();
    }
}
