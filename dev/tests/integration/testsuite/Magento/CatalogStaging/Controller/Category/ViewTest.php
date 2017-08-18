<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogStaging\Controller\Category;

use Magento\Backend\Model\Auth;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Reports\Block\Product\Widget\Viewed as WidgetViewedProducts;
use Magento\TestFramework\Bootstrap;
use Magento\TestFramework\TestCase\AbstractController;

/**
 * Test class for category view controller with staging.
 * @magentoAppArea adminhtml
 */
class ViewTest extends AbstractController
{
    /**
     * Tests that getting product items in block "Recently Viewed Products"
     * works correct on category scheduled updates preview page.
     *
     * @magentoDataFixture Magento/Catalog/_files/category.php
     */
    public function testPreviewWithRecentlyViewedProductsWidget()
    {
        $categoryId = 333;
        $this->_objectManager->get(Auth::class)->login(
            Bootstrap::ADMIN_NAME,
            Bootstrap::ADMIN_PASSWORD
        );

        /** @var RequestInterface $request */
        $request = $this->_objectManager->get(RequestInterface::class);
        $request->setParam('___version', 1);

        $this->dispatch("catalog/category/view/id/{$categoryId}");

        /** @var WidgetViewedProducts $viewedProducts */
        $viewedProducts = $this->_objectManager->get(WidgetViewedProducts::class);

        try {
            $viewedProducts->getItemsCollection()->getSize();
        } catch (\Exception $e) {
            $this->fail(
                'Getting product items in block "Recently Viewed Products" ' .
                'generates an exception on category scheduled updates preview page: ' .
                $e->getMessage()
            );
        }
    }
}
