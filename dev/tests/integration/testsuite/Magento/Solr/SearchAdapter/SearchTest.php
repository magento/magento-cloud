<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Solr\SearchAdapter;

use Magento\Solr\Helper\Data;

/**
 * Search test.
 *
 * @magentoDbIsolation disabled
 * @magentoAppIsolation enabled
 */
class SearchTest extends \Magento\Search\Model\SearchTest
{
    /**
     * @var string
     */
    protected $searchEngine = Data::SOLR;

    /**
     * Returns search adapter instance.
     *
     * @return \Magento\Framework\Search\AdapterInterface
     */
    protected function createAdapter()
    {
        return $this->objectManager->create(\Magento\Solr\SearchAdapter\Adapter::class);
    }

    /**
     * Search grouped product.
     *
     * @magentoDataFixture Magento/Framework/Search/_files/grouped_product.php
     * @magentoConfigFixture current_store catalog/search/engine solr
     *
     * @return void
     */
    public function testSearchGroupedProduct()
    {
        parent::testSearchGroupedProduct();
    }
}
