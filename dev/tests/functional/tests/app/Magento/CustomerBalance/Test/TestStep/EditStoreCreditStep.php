<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerBalance\Test\TestStep;

use Magento\ImportExport\Test\Fixture\ImportData;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Edit customers store credits.
 */
class EditStoreCreditStep implements TestStepInterface
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
    private $storeCredits;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param ImportData $import
     * @param array $storeCredits
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        ImportData $import,
        array $storeCredits
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->import = $import;
        $this->storeCredits = $storeCredits;
    }

    /**
     * Edit customers store credits.
     *
     * @return void
     */
    public function run()
    {
        $customers = $this->import->getDataFieldConfig('import_file')['source']->getEntities();
        foreach ($this->storeCredits as $key => $storeCredits) {
            $customerBalance = $this->fixtureFactory->createByCode(
                'customerBalance',
                [
                    'dataset' => $storeCredits,
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
