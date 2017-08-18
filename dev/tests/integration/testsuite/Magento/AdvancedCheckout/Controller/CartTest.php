<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout\Controller;

class CartTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Bootstrap application before any test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->productRepository = $this->_objectManager->create(
            \Magento\Catalog\Api\ProductRepositoryInterface::class
        );
    }

    /**
     * @magentoDataFixture Magento/AdvancedCheckout/_files/quote_with_gift_card_product.php
     * @magentoAppArea frontend
     */
    public function testConfigureActionWithGiftCardProduct()
    {
        /** @var $session \Magento\Checkout\Model\Session  */
        $session = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Checkout\Model\Session::class
        );
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $this->productRepository->get('gift-card');

        $quoteItem = $this->_getQuoteItemIdByProductId($session->getQuote(), $product->getId());

        $this->dispatch(
            'checkout/cart/configure/id/' . $quoteItem->getId() . '/product_id/' . $quoteItem->getProduct()->getId()
        );
        $response = $this->getResponse();

        $this->assertSessionMessages($this->isEmpty(), \Magento\Framework\Message\MessageInterface::TYPE_ERROR);

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//button[@type="submit" and @title="Update Cart"]',
                $response->getBody()
            ),
            'Response for gift card product doesn\'t contain "Update Cart" button'
        );

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//input[@id="giftcard-amount-input" and @type="text"]',
                $response->getBody()
            ),
            'Response for gift card product doesn\'t contain gift card amount input field'
        );
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testConfigureFailedActionWithSimpleProduct()
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $this->productRepository->get('simple');
        $this->dispatch('checkout/cart/configureFailed/id/' . $product->getId());
        $response = $this->getResponse();

        $this->assertSessionMessages($this->isEmpty(), \Magento\Framework\Message\MessageInterface::TYPE_ERROR);

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//button[@type="submit" and @title="Update Cart"]',
                $response->getBody()
            ),
            'Response for simple product doesn\'t contain "Update Cart" button'
        );
    }

    /**
     * @magentoDataFixture Magento/Bundle/_files/product.php
     */
    public function testConfigureFailedActionWithBundleProduct()
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $this->productRepository->get('bundle-product');
        $this->dispatch('checkout/cart/configureFailed/id/' . $product->getId());
        $response = $this->getResponse();

        $this->assertSessionMessages($this->isEmpty(), \Magento\Framework\Message\MessageInterface::TYPE_ERROR);

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//button[@type="submit" and @title="Update Cart"]',
                $response->getBody()
            ),
            'Response for bundle product doesn\'t contain "Update Cart" button'
        );
    }

    /**
     * @magentoDataFixture Magento/Downloadable/_files/product_downloadable.php
     */
    public function testConfigureFailedActionWithDownloadableProduct()
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $this->productRepository->get('downloadable-product');
        $this->dispatch('checkout/cart/configureFailed/id/' . $product->getId());
        $response = $this->getResponse();

        $this->assertSessionMessages($this->isEmpty(), \Magento\Framework\Message\MessageInterface::TYPE_ERROR);

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//button[@type="submit" and @title="Update Cart"]',
                $response->getBody()
            ),
            'Response for downloadable product doesn\'t contain "Update Cart" button'
        );

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//*[@id="downloadable-links-list"]',
                $response->getBody()
            ),
            'Response for downloadable product doesn\'t contain links for download'
        );
    }

    /**
     * @magentoDataFixture Magento/GiftCard/_files/gift_card.php
     */
    public function testConfigureFailedActionWithGiftCardProduct()
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $this->productRepository->get('gift-card');
        $this->dispatch('checkout/cart/configureFailed/id/' . $product->getId());
        $response = $this->getResponse();

        $this->assertSessionMessages($this->isEmpty(), \Magento\Framework\Message\MessageInterface::TYPE_ERROR);

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//button[@type="submit" and @title="Update Cart"]',
                $response->getBody()
            ),
            'Response for gift card product doesn\'t contain "Update Cart" button'
        );

        $this->assertEquals(
            1,
            \Magento\TestFramework\Helper\Xpath::getElementsCountForXpath(
                '//input[@id="giftcard-amount-input" and @type="text"]',
                $response->getBody()
            ),
            'Response for gift card product doesn\'t contain gift card amount input field'
        );
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param $productId
     * @return \Magento\Quote\Model\Quote\Item|null
     */
    private function _getQuoteItemIdByProductId($quote, $productId)
    {
        /** @var $quoteItems \Magento\Quote\Model\Quote\Item[] */
        $quoteItems = $quote->getAllItems();
        foreach ($quoteItems as $quoteItem) {
            if ($productId == $quoteItem->getProductId()) {
                return $quoteItem;
            }
        }
        return null;
    }
}
