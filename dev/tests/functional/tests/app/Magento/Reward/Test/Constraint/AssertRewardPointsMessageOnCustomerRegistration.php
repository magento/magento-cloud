<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertRewardPointsMessageOnCustomerRegistration
 * Assert that reward points message is appeared on the Create New Customer Account page
 */
class AssertRewardPointsMessageOnCustomerRegistration extends AbstractConstraint
{
    /**
     * Message about reward points on registration page
     */
    const REGISTRATION_REWARD_MESSAGE = 'Create an account on our site now and earn %d Reward points.';

    /**
     * Assert that reward points message is appeared on the Create New Customer Account page
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountCreate $customerAccountCreate
     * @param string $registrationReward
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CustomerAccountCreate $customerAccountCreate,
        $registrationReward
    ) {
        $cmsIndex->open();
        $cmsIndex->getLinksBlock()->openLink('Create an Account');

        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::REGISTRATION_REWARD_MESSAGE, $registrationReward),
            trim($customerAccountCreate->getTooltipBlock()->getRewardMessage()),
            'Wrong message about registration reward is displayed.'
        );
    }

    /**
     * Returns a string representation of successful assertion
     *
     * @return string
     */
    public function toString()
    {
        return 'Reward points message is appeared on create new customer account page.';
    }
}
