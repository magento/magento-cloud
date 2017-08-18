<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesRuleStaging\Model\Coupon;

use Magento\SalesRule\Model\Rule as SalesRule;

class ExpirationDateResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoDataFixture Magento/SalesRule/_files/rules.php
     * @magentoDataFixture Magento/SalesRule/_files/coupons.php
     */
    public function testSetValidationFilter()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\SalesRule\Model\ResourceModel\Rule\Collection $collection */
        $collection = $objectManager->create(
            \Magento\SalesRule\Model\ResourceModel\Rule\Collection::class
        );
        $items = array_values(
            $collection->addFieldToFilter('coupon_type', ['nin' => SalesRule::COUPON_TYPE_NO_COUPON])->getItems()
        );

        $ids = [];
        $expirationDates = [];

        /** @var  SalesRule $item */
        foreach ($items as $item) {
            $ruleId = $item->getId();
            $ids[] = $ruleId;
            $expirationDates[$ruleId] = $item->getToDate();
        }
        $this->assertNotEmpty($ids, 'Rule ids not found');
        /** @var  \Magento\SalesRuleStaging\Model\Coupon\ExpirationDateResolver $expirationDataResolver */
        $experationDataResolver = $objectManager->create(
            \Magento\SalesRuleStaging\Model\Coupon\ExpirationDateResolver::class
        );
        $observer = $objectManager->create(
            \Magento\Framework\Event\Observer::class,
            ['data' => ['entity_ids' => $ids]]
        );
        $experationDataResolver->execute($observer);
        $couponCollection = $objectManager->create(\Magento\SalesRule\Model\ResourceModel\Coupon\Collection::class);
        $coupons = $couponCollection->addFieldToFilter('rule_id', ['in' => $ids]);
        /** @var \Magento\SalesRule\Api\Data\CouponInterface $coupon */
        foreach ($coupons as $coupon) {
            $this->assertEquals($coupon->getExperationDate(), $expirationDates[$coupon->getRuleId()]);
        }
    }
}
