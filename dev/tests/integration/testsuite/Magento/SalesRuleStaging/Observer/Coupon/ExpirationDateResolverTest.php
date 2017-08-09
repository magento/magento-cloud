<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesRuleStaging\Observer\Coupon;

use Magento\Framework\Event\Observer;
use Magento\Framework\ObjectManagerInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\SalesRule\Model\ResourceModel\Coupon\Collection as CouponCollection;
use Magento\SalesRule\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\SalesRule\Model\Rule as SalesRule;
use Magento\TestFramework\Helper\Bootstrap;

/**
 * Tests ExpirationDateResolver.
 */
class ExpirationDateResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/SalesRule/_files/rules.php
     * @magentoDataFixture Magento/SalesRule/_files/coupons.php
     */
    public function testSetValidationFilter()
    {
        /** @var ObjectManagerInterface $objectManager */
        $objectManager = Bootstrap::getObjectManager();

        /** @var RuleCollection $collection */
        $collection = $objectManager->create(
            RuleCollection::class
        );

        /** @var SalesRule[] $items */
        $items = array_values(
            $collection->addFieldToFilter(
                'coupon_type',
                ['nin' => SalesRule::COUPON_TYPE_NO_COUPON]
            )->getItems()
        );

        /** @var array $ids */
        $ids = [];

        /** @var array $expirationDates */
        $expirationDates = [];

        /** @var SalesRule $item */
        foreach ($items as $item) {
            /** @var string $ruleId */
            $ruleId = $item->getId();
            $ids[] = $ruleId;
            $expirationDates[$ruleId] = $item->getToDate();
        }

        $this->assertNotEmpty($ids, 'Rule ids not found');

        /** @var Observer $observer */
        $observer = $objectManager->create(
            Observer::class,
            ['data' => ['entity_ids' => $ids]]
        );

        /** @var ExpirationDateResolver $expirationDataResolver */
        $experationDataResolver = $objectManager->create(
            ExpirationDateResolver::class
        );
        $experationDataResolver->execute($observer);

        /** @var CouponCollection $couponCollection */
        $couponCollection = $objectManager->create(CouponCollection::class);
        $couponCollection->addFieldToFilter('rule_id', ['in' => $ids]);

        /** @var CouponInterface $coupon */
        foreach ($couponCollection as $coupon) {
            $this->assertEquals(
                $coupon->getExpirationDate(),
                $expirationDates[$coupon->getRuleId()]
            );
        }
    }
}
