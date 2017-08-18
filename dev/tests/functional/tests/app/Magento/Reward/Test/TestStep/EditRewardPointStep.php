<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reward\Test\TestStep;

use Magento\ImportExport\Test\Fixture\ImportData;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Edit customers reward points.
 */
class EditRewardPointStep implements TestStepInterface
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Import fixture.
     *
     * @var ImportData
     */
    private $import;

    /**
     * Customers reward points.
     *
     * @var array
     */
    private $rewardPoints;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param ImportData $import
     * @param array $rewardPoints
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        ImportData $import,
        array $rewardPoints
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->import = $import;
        $this->rewardPoints = $rewardPoints;
    }

    /**
     * Edit customers reward points.
     *
     * @return void
     */
    public function run()
    {
        $customers = $this->import->getDataFieldConfig('import_file')['source']->getEntities();
        foreach ($this->rewardPoints as $key => $rewardPoint) {
            $customerBalance = $this->fixtureFactory->createByCode(
                'customerBalance',
                [
                    'dataset' => $rewardPoint,
                    'data' => [
                        'customer_id' => [
                            'customer' => $customers[$key]
                        ],
                        'website_id' => [
                            'fixture' => $customers[$key]->getDataFieldConfig('website_id')['source']->getWebsite()
                        ]
                    ]
                ]
            );
            $customerBalance->persist();
        }
    }
}
