<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Solr\SearchAdapter;

use Magento\Solr\Helper\Data;

/**
 * Class AdapterTest
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
     * Get request config path
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
        return $this->objectManager->create(\Magento\Solr\SearchAdapter\Adapter::class);
    }

    /**
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testMatchQuery()
    {
        parent::testMatchQuery();
    }

    /**
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testMatchOrderedQuery()
    {
        $this->markTestSkipped('Solr search not expected to order results by default. Test is skipped intentionally.');
    }

    /**
     * @magentoConfigFixture current_store catalog/search/engine solr
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
     * Range filter test with all fields filled
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testRangeFilterWithAllFields()
    {
        parent::testRangeFilterWithAllFields();
    }

    /**
     * Range filter test with all fields filled
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testRangeFilterWithoutFromField()
    {
        parent::testRangeFilterWithoutFromField();
    }

    /**
     * Range filter test with all fields filled
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testRangeFilterWithoutToField()
    {
        parent::testRangeFilterWithoutToField();
    }

    /**
     * Term filter test
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testTermFilter()
    {
        parent::testTermFilter();
    }

    /**
     * Term filter test
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testTermFilterArray()
    {
        parent::testTermFilterArray();
    }

    /**
     * Term filter test
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testWildcardFilter()
    {
        parent::testWildcardFilter();
    }

    /**
     * Request limits test
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testSearchLimit()
    {
        parent::testSearchLimit();
    }

    /**
     * Bool filter test
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testBoolFilter()
    {
        parent::testBoolFilter();
    }

    /**
     * Test bool filter with nested negative bool filter
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testBoolFilterWithNestedNegativeBoolFilter()
    {
        parent::testBoolFilterWithNestedNegativeBoolFilter();
    }

    /**
     * Test range inside nested negative bool filter
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testBoolFilterWithNestedRangeInNegativeBoolFilter()
    {
        parent::testBoolFilterWithNestedRangeInNegativeBoolFilter();
    }

    /**
     * Sample Advanced search request test
     *
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @dataProvider advancedSearchDataProvider
     * @param string $nameQuery
     * @param string $descriptionQuery
     * @param array $rangeFilter
     * @param int $expectedRecordsCount
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
     */
    public function testCustomFilterableAttribute()
    {
        parent::testCustomFilterableAttribute();
    }

    /**
     * Test filtering by two attributes.
     *
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Framework/Search/_files/filterable_attributes.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     * @dataProvider filterByAttributeValuesDataProvider
     * @param string $requestName
     * @param array $additionalData
     * @return void
     */
    public function testFilterByAttributeValues($requestName, $additionalData)
    {
        parent::testFilterByAttributeValues($requestName, $additionalData);
    }

    /**
     * Advanced search request using date product attribute
     *
     * @param $rangeFilter
     * @param $expectedRecordsCount
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
     */
    public function testAdvancedSearchCompositeProductWithOutOfStockOption()
    {
        $this->markTestSkipped('Filter of composite products with Out of Stock child not supported till MAGETWO-56525');
        parent::testAdvancedSearchCompositeProductWithOutOfStockOption();
    }

    /**
     * @magentoDataFixture Magento/Framework/Search/_files/product_configurable_with_disabled_child.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testAdvancedSearchCompositeProductWithDisabledChild()
    {
        $this->markTestSkipped('Filter of composite products with Out of Stock child not supported till MAGETWO-56525');
        parent::testAdvancedSearchCompositeProductWithDisabledChild();
    }

    /**
     * Test for search weight customization to ensure that search weight works correctly and affects search results.
     *
     * @magentoDataFixture Magento/Framework/Search/_files/search_weight_products.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     */
    public function testSearchQueryBoost()
    {
        $this->markTestSkipped(
            'Dynamic boosting of search query not supported in Solr.'
            . ' Use Solr configuration to customize search results'
        );
        parent::testSearchQueryBoost();
    }
}
