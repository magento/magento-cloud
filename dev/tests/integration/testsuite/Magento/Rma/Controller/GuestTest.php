<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Controller;

class GuestTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @param string $uri
     * @param string $content
     * @magentoConfigFixture current_store sales/magento_rma/enabled 1
     * @magentoDataFixture Magento/Rma/_files/rma.php
     * @dataProvider isResponseContainDataProvider
     */
    public function testIsResponseContain($uri, $content)
    {
        /** @var $rma \Magento\Rma\Model\Rma */
        $rma = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Rma\Model\Rma::class);
        $rma->load(1, 'increment_id');

        $this->getRequest()->setParam('entity_id', $rma->getEntityId());
        $this->getRequest()->setPostValue('oar_type', 'email');
        $this->getRequest()->setPostValue('oar_order_id', $rma->getOrder()->getIncrementId());
        $billingAddress = $rma->getOrder()->getBillingAddress();
        if ($billingAddress !== false) {
            $this->getRequest()->setPostValue('oar_billing_lastname', $billingAddress->getLastname());
            $this->getRequest()->setPostValue('oar_email', $billingAddress->getEmail());
        }
        $this->getRequest()->setPostValue('oar_zip', '');

        $this->dispatch($uri);
        $this->assertContains($content, $this->getResponse()->getBody());
    }

    public function isResponseContainDataProvider()
    {
        return [
            ['rma/guest/addlabel', 'class="col carrier">CarrierTitle</td>'],
            ['rma/guest/dellabel', 'class="col carrier">CarrierTitle</td>']
        ];
    }
}
