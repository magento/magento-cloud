<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Elasticsearch\SearchAdapter;

use Magento\Elasticsearch\Model\Config;

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
    protected $searchEngine = Config::ENGINE_NAME;

    /**
     * Returns search adapter instance.
     *
     * @return \Magento\Framework\Search\AdapterInterface
     */
    protected function createAdapter()
    {
        return $this->objectManager->create(\Magento\Elasticsearch\SearchAdapter\Adapter::class);
    }

    /**
     * Search grouped product.
     *
     * @magentoDataFixture Magento/Framework/Search/_files/grouped_product.php
     * @magentoConfigFixture current_store catalog/search/engine elasticsearch
     * @magentoConfigFixture current_store catalog/search/elasticsearch_index_prefix adaptertest
     *
     * @return void
     */
    public function testSearchGroupedProduct()
    {
        parent::testSearchGroupedProduct();
    }
}
