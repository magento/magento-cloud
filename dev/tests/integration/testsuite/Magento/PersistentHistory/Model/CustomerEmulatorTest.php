<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\PersistentHistory\Model;

class CustomerEmulatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var \Magento\PersistentHistory\Model\CustomerEmulator
     */
    protected $_model;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Persistent\Helper\Session
     */
    protected $_persistentSessionHelper;

    /**
     * @var \Magento\Wishlist\Helper\Data
     */
    protected $_wishlistData;

    /**
     * @var \Magento\Persistent\Model\SessionFactory
     */
    protected $_sessionFactory;

    protected function setUp()
    {
        $this->_objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->_customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
        $this->_persistentSessionHelper = $this->_objectManager->create('Magento\Persistent\Helper\Session');
        $this->_wishlistData = $this->_objectManager->create('Magento\Wishlist\Helper\Data');
        $this->_model = $this->_objectManager->create(
            'Magento\PersistentHistory\Model\CustomerEmulator',
            [
                'customerSession' => $this->_customerSession,
                'persistentSession' => $this->_persistentSessionHelper,
                'wishlistData' => $this->_wishlistData
            ]
        );

        $this->_sessionFactory = $this->_objectManager->create('Magento\Persistent\Model\SessionFactory');
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     * @magentoConfigFixture current_store persistent/options/customer 1
     * @magentoConfigFixture current_store persistent/options/enabled 1
     * @magentoConfigFixture current_store persistent/options/wishlist 1
     */
    public function testEmulateCustomerWishlist()
    {
        /** @var \Magento\Persistent\Model\Session $sessionModel */
        $sessionModel = $this->_sessionFactory->create();
        $sessionModel->setCustomerId(1)->save();

        $this->_persistentSessionHelper->setSession($sessionModel);

        $this->_model->emulate();

        $expectedCustomer = $this->_customerSession->getCustomerDataObject();
        $actualCustomer = $this->_wishlistData->getCustomer();

        $this->assertEquals($expectedCustomer->getId(), $actualCustomer->getId());
        $this->assertEquals($expectedCustomer->getEmail(), $actualCustomer->getEmail());
        $this->assertEquals($expectedCustomer->getWebsiteId(), $actualCustomer->getWebsiteId());
        $this->assertEquals($expectedCustomer->getStoreId(), $actualCustomer->getStoreId());
        $this->assertEquals($expectedCustomer->getGroupId(), $actualCustomer->getGroupId());
        $this->assertEquals($expectedCustomer->getFirstname(), $actualCustomer->getFirstname());
        $this->assertEquals($expectedCustomer->getLastname(), $actualCustomer->getLastname());
        $this->assertEquals($expectedCustomer->getCreatedAt(), $actualCustomer->getCreatedAt());

        $expectedCustomAttributes = $expectedCustomer->getCustomAttributes();
        $actualCustomAttributes = $actualCustomer->getCustomAttributes();
        $this->assertCount(count($expectedCustomAttributes), $actualCustomAttributes);
        foreach ($expectedCustomAttributes as $key => $attribute) {
            $this->assertArrayHasKey($key, $actualCustomAttributes);
            $this->assertEquals($attribute->getValue(), $actualCustomAttributes[$key]->getValue());
        }
    }
}
