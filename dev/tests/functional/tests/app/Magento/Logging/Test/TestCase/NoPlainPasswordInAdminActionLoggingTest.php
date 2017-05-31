<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\TestCase;

use Magento\User\Test\Fixture\User;
use Magento\User\Test\Page\Adminhtml\UserEdit;
use Magento\User\Test\Page\Adminhtml\UserIndex;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test that no plain admin password is displayed in admin action logging.
 *
 * Test Flow:
 * 1. Log in as default admin user
 * 2. Go to System -> Permissions -> All Users
 * 3. Click 'Add New User' button to create a new admin user
 * 4. Fill in all data according to data set
 * 5. Save user
 * 6. Perform assertions
 *
 * @group Magento_Logging
 * @ZephyrId MAGETWO-64395
 * @security_private
 */
class NoPlainPasswordInAdminActionLoggingTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * User grid page
     *
     * @var UserIndex
     */
    protected $userIndexPage;

    /**
     * User new/edit page
     *
     * @var UserEdit
     */
    protected $userEditPage;

    /**
     * Factory for Fixtures
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Setup necessary data for test.
     *
     * @param UserIndex $userIndex
     * @param UserEdit $userEdit
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        UserIndex $userIndex,
        UserEdit $userEdit,
        FixtureFactory $fixtureFactory
    ) {
        $this->userIndexPage = $userIndex;
        $this->userEditPage = $userEdit;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Prepare test by creating a new admin user.
     *
     * @param User $user
     * @return array
     */
    public function test(User $user)
    {
        // Prepare data
        $data = $user->getData();
        $data['role_id'] = ['role' => $user->getDataFieldConfig('role_id')['source']->getRole()];
        $user = $this->fixtureFactory->createByCode('user', ['data' => $data]);

        // Steps
        $this->userIndexPage->open();
        $this->userIndexPage->getPageActions()->addNew();
        $this->userEditPage->getUserForm()->fill($user);
        $this->userEditPage->getPageActions()->save();

        return ['customAdmin' => $user];
    }
}
