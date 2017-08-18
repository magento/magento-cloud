<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\TestCase;

use Magento\Customer\Test\Fixture\Address;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;

/**
 * Abstract class for create customer segment.
 */
abstract class AbstractCreateCustomerSegmentEntityTest extends Injectable
{
    /**
     * Customer segment index page.
     *
     * @var CustomerSegmentIndex
     */
    protected $customerSegmentIndex;

    /**
     * Page of create new customer segment.
     *
     * @var CustomerSegmentNew
     */
    protected $customerSegmentNew;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Inject pages.
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew,
        FixtureFactory $fixtureFactory
    ) {
        $this->customerSegmentIndex = $customerSegmentIndex;
        $this->customerSegmentNew = $customerSegmentNew;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Update customer. Create customer segment.
     *
     * @param array $customers
     * @param int $customerIndex
     * @param CustomerSegment $customerSegment
     * @param CustomerSegment $customerSegmentConditions
     * @param Address|null $address [optional]
     * @param bool $isAddCustomerBalance [optional]
     * @param CustomerSegment|null $customerSegment2 [optional]
     * @param CustomerCustomAttribute|null $customerCustomAttribute [optional]
     * @return array
     */
    public function test(
        array $customers,
        $customerIndex,
        CustomerSegment $customerSegment,
        CustomerSegment $customerSegmentConditions,
        Address $address = null,
        $isAddCustomerBalance = false,
        CustomerSegment $customerSegment2 = null,
        CustomerCustomAttribute $customerCustomAttribute = null
    ) {
        foreach ($customers as $key => $customerDataset) {
            $customers[$key] = $this->fixtureFactory->createByCode(
                'customer',
                [
                    'dataset' => $customerDataset,
                    'data' => array_merge(
                        $address ? ['address' => ['addresses' => [$address]]] : [],
                        $customerCustomAttribute ?
                        ['custom_attribute' => $customerCustomAttribute->getData()] : []
                    )
                ]
            );
            $customers[$key]->persist();
        }

        if ($isAddCustomerBalance) {
            $customerBalance = $this->fixtureFactory->createByCode(
                'customerBalance',
                [
                    'dataset' => 'customerBalance_100',
                    'data' => ['customer_id' => ['customer' => $customers[$customerIndex]]]
                ]
            );
            $customerBalance->persist();
        }

        if ($customerSegment2) {
            $customerSegment2->persist();
        }

        if (!$customerSegment->getWebsiteIds()) {
            $customerSegment = $this->fixtureFactory->createByCode(
                'customerSegment',
                [
                    'data' => array_merge(
                        $customerSegment->getData(),
                        ['website_ids' => $customers[$customerIndex]->getWebsiteId()]
                    )
                ]
            );
        }

        //Prepare data
        $customerAddress = $customers[$customerIndex]->getDataFieldConfig('address')['source']->getAddresses()[0];
        $replace = [
            'conditions' => [
                '%email%' => $customers[$customerIndex]->getEmail(),
                '%company%' => $customerAddress->getCompany(),
                '%address%' => $customerAddress->getStreet(),
                '%telephone%' => $customerAddress->getTelephone(),
                '%postcode%' => $customerAddress->getPostcode(),
                '%province%' => $customerAddress->getRegionId(),
                '%city%' => $customerAddress->getCity(),
                '%country%' => $customerAddress->getCountryId(),
            ],
        ];

        if (isset($customerCustomAttribute)) {
            $replace['conditions']['%attribute_label%'] = $customerCustomAttribute->getFrontendLabel();
            preg_match_all('`option_\d+`', $customerSegmentConditions->getConditionsSerialized(), $matches);
            $replace['conditions']['%' . $matches[0][0] . "%"] =
                $customerCustomAttribute->getOption()['value'][$matches[0][0]][0];
        }

        //Steps
        $this->customerSegmentIndex->open();
        $this->customerSegmentIndex->getPageActionsBlock()->addNew();
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $this->customerSegmentNew->getPageMainActions()->saveAndContinue();
        $this->customerSegmentNew->getMessagesBlock()->waitSuccessMessage();
        $this->customerSegmentNew->getCustomerSegmentForm()->openTab('conditions');
        $this->customerSegmentNew->getCustomerSegmentForm()->fill($customerSegmentConditions, null, $replace);
        $this->customerSegmentNew->getPageMainActions()->save();

        return [
            'customer' => $customers[$customerIndex],
        ];
    }

    /**
     * Deleting cart price rules and customer segments.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\SalesRule\Test\TestStep\DeleteAllSalesRuleStep::class)->run();
        $this->objectManager->create(\Magento\CustomerSegment\Test\TestStep\DeleteAllCustomerSegmentsStep::class)
            ->run();
    }
}
