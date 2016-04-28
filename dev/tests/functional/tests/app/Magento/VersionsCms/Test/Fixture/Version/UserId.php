<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Fixture\Version;

use Magento\User\Test\Fixture\User;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Prepare Owner for Cms Page Version.
 *
 * Data keys:
 *  - dataset
 */
class UserId implements FixtureInterface
{
    /**
     * Array with user names.
     *
     * @var string
     */
    protected $data;

    /**
     * Array with user fixture.
     *
     * @var User
     */
    protected $user;

    /**
     * Data set configuration settings.
     *
     * @var array
     */
    protected $params;

    /**
     * Create custom user if needed.
     *
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->params = $params;
        if (isset($data['dataset'])) {
            /** @var \Magento\User\Test\Fixture\User $user */
            $user = $fixtureFactory->createByCode('user', ['dataset' => $data['dataset']]);
            if (!$user->hasData('user_id')) {
                $user->persist();
            }
            $this->user = $user;
            $this->data = $user->getUsername();
        }
    }

    /**
     * Persist custom user.
     *
     * @return void
     */
    public function persist()
    {
        //
    }

    /**
     * Return prepared data set.
     *
     * @param string|null $key [optional]
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getData($key = null)
    {
        return $this->data;
    }

    /**
     * Return user.
     *
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return data set configuration settings.
     *
     * @return array
     */
    public function getDataConfig()
    {
        return $this->params;
    }
}
