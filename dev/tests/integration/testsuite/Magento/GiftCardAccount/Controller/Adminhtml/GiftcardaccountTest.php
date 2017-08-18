<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Controller\Adminhtml;

/**
 * @magentoAppArea adminhtml
 */
class GiftcardaccountTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function testIndexAction()
    {
        $this->dispatch('backend/admin/giftcardaccount/index');
        $this->assertContains(
            "Code Pool used: <b>100%</b>",
            $this->getResponse()->getBody()
        );
    }
}
