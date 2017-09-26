<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Setup\Fixtures;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 2.1.0
 */
class CustomerSegmentsFixture extends Fixture
{
    /**
     * @var int
     * @since 2.1.0
     */
    protected $priority = 75;

    /**
     * @var float
     * @since 2.1.0
     */
    protected $customerSegmentsCount = 0;

    /**
     * @var float
     * @since 2.1.0
     */
    protected $customerSegmentRulesCount = 0;

    /**
     * @var float
     * @since 2.1.0
     */
    protected $cartPriceRulesCount = 0;

    /**
     * @var bool
     * @since 2.1.0
     */
    protected $cartRulesAdvancedType = false;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     * @since 2.2.0
     */
    private $ruleFactory;

    /**
     * @var \Magento\CustomerSegment\Model\SegmentFactory
     * @since 2.2.0
     */
    private $segmentFactory;

    /**
     * Constructor
     *
     * @param FixtureModel $fixtureModel
     * @param \Magento\SalesRule\Model\RuleFactory|null $ruleFactory
     * @param \Magento\CustomerSegment\Model\SegmentFactory|null $segmentFactory
     * @since 2.2.0
     */
    public function __construct(
        FixtureModel $fixtureModel,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory = null,
        \Magento\CustomerSegment\Model\SegmentFactory $segmentFactory = null
    ) {
        parent::__construct($fixtureModel);
        $this->ruleFactory = $ruleFactory ?: $this->fixtureModel->getObjectManager()
            ->get(\Magento\SalesRule\Model\RuleFactory::class);
        $this->segmentFactory = $segmentFactory ?: $this->fixtureModel->getObjectManager()
            ->get(\Magento\CustomerSegment\Model\SegmentFactory::class);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD)
     * @since 2.1.0
     */
    public function execute()
    {
        $this->fixtureModel->resetObjectManager();
        $this->customerSegmentRulesCount = $this->fixtureModel->getValue('customer_segment_rules', 0);
        if (!$this->customerSegmentRulesCount) {
            return;
        }
        $this->cartPriceRulesCount = $this->fixtureModel->getValue('cart_price_rules', 0);
        $this->customerSegmentsCount = $this->fixtureModel->getValue('customer_segments', 1);

        /** @var \Magento\Store\Model\StoreManager $storeManager */
        $storeManager = $this->fixtureModel->getObjectManager()->create(\Magento\Store\Model\StoreManager::class);
        /** @var $category \Magento\Catalog\Model\Category */
        $category = $this->fixtureModel->getObjectManager()->get(\Magento\Catalog\Model\Category::class);

        //Get all websites
        $categoriesArray = [];
        $websites = $storeManager->getWebsites();
        foreach ($websites as $website) {
            //Get all groups
            $websiteGroups = $website->getGroups();
            foreach ($websiteGroups as $websiteGroup) {
                $websiteGroupRootCategory = $websiteGroup->getRootCategoryId();
                $category->load($websiteGroupRootCategory);
                $categoryResource = $category->getResource();
                //Get all categories
                $resultsCategories = $categoryResource->getAllChildren($category);
                foreach ($resultsCategories as $resultsCategory) {
                    $category->load($resultsCategory);
                    $structure = explode('/', $category->getPath());
                    if (count($structure) > 2) {
                        $categoriesArray[] = [$category->getId(), $website->getId()];
                    }
                }
            }
        }

        asort($categoriesArray);
        $categoriesArray = array_values($categoriesArray);

        // create customer segments
        $this->generateCustomerSegments();
        $this->generateCustomerSegmentRules($this->ruleFactory, $categoriesArray);
    }

    /**
     * Creates customer segments and conditions
     * @return void
     * @since 2.1.0
     */
    public function generateCustomerSegments()
    {
        // Map x customers to y segments
        $numCustomers = $this->fixtureModel->getValue('customers', 0);

        // Create Segments
        $total = 1;
        for ($i = 0; $i <  $this->customerSegmentsCount; $i++) {
            $ruleName = sprintf('Customer Segment %1$d', $i);

            $data = [
                'name'          => $ruleName,
                'website_ids'   => [1],
                'is_active'     => '1',
                'apply_to'      => 0,
            ];

            $segment = $this->segmentFactory->create();
            $segment->loadPost($data);
            $segment->save();
            $segmentId = $segment->getSegmentId();

            // Add Conditions
            $conditions = [
                1 => [
                    'type' => \Magento\CustomerSegment\Model\Segment\Condition\Combine\Root::class,
                    'aggregator' => 'any',
                    'value' => '1',
                    'new_child' => '',
                ]
            ];
            for ($j = 1; $j <= ($numCustomers / $this->customerSegmentsCount); $j++) {
                $conditionId = sprintf('1--%1$d', $j);
                $value = sprintf('user_%1$d@example.com', $total);
                $condition = [
                    'type' => \Magento\CustomerSegment\Model\Segment\Condition\Customer\Attributes::class,
                    'attribute' => 'email',
                    'operator' => '==',
                    'value' => $value,
                ];
                $conditions[$conditionId] = $condition;
                $total++;
            }

            $data = [
                'name'          => $ruleName,
                'segment_id'    => $segmentId,
                'website_ids'   => [1],
                'is_active'     => '1',
                'conditions'    => $conditions
            ];

            $segment->loadPost($data);
            $segment->save();

            if ($segment->getApplyTo() != \Magento\CustomerSegment\Model\Segment::APPLY_TO_VISITORS) {
                $segment->matchCustomers();
            }
        }
    }

    /**
     * @param int $ruleId
     * @param array $categoriesArray
     * @return array
     * @since 2.1.0
     */
    public function generateSegmentCondition($ruleId, $categoriesArray)
    {
        // Category
        $firstCondition = [
            'type'      => \Magento\SalesRule\Model\Rule\Condition\Product::class,
            'attribute' => 'category_ids',
            'operator'  => '==',
            'value'     => $categoriesArray[(($ruleId - $this->cartPriceRulesCount) / 4 ) % count($categoriesArray)][0],
        ];

        $subtotal = [0, 5, 10, 15];
        // Subtotal
        $secondCondition = [
            'type'      => \Magento\SalesRule\Model\Rule\Condition\Address::class,
            'attribute' => 'base_subtotal',
            'operator'  => '>=',
            'value'     => $subtotal[$ruleId % 4],
        ];

        // Customer Segment
        $thirdCondition = [
            'type'      => \Magento\CustomerSegment\Model\Segment\Condition\Segment::class,
            'operator'  => '==',
            'value'     =>  ((($ruleId - $this->cartPriceRulesCount) / 4) % $this->customerSegmentsCount) + 1,
        ];

        return [
            'conditions' => [
                1 => [
                    'type' => \Magento\SalesRule\Model\Rule\Condition\Combine::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1'=> [
                    'type' => \Magento\SalesRule\Model\Rule\Condition\Product\Found::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
                '1--1--1' => $firstCondition,
                '1--2' => $secondCondition,
                '1--3' => $thirdCondition,
            ],
            'actions' => [
                1 => [
                    'type' => \Magento\SalesRule\Model\Rule\Condition\Product\Combine::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => '',
                ],
            ]
        ];
    }

    /**
     * @param \Magento\SalesRule\Model\RuleFactory $ruleFactory
     * @param array $categoriesArray
     * @return void
     * @since 2.1.0
     */
    public function generateCustomerSegmentRules($ruleFactory, $categoriesArray)
    {
        for ($i = $this->cartPriceRulesCount;
            $i < ($this->customerSegmentRulesCount + $this->cartPriceRulesCount); $i++) {
            $ruleName = sprintf('Cart Price Advanced Customer Segment Rule %1$d', $i);
            $data = [
                'rule_id'               => null,
                'product_ids'           => '',
                'name'                  => $ruleName,
                'description'           => '',
                'is_active'             => '1',
                'website_ids'           => $categoriesArray[$i % count($categoriesArray)][1],
                'customer_group_ids'    => [
                    0 => '0',
                    1 => '1',
                    2 => '2',
                    3 => '3',
                ],
                'coupon_type'           => '1',
                'coupon_code'           => '',
                'uses_per_customer'     => '',
                'from_date'             => '',
                'to_date'               => '',
                'sort_order'            => '',
                'is_rss'                => '1',
                'rule'                  => $this->generateSegmentCondition($i, $categoriesArray),
                'simple_action'             => 'cart_fixed',
                'discount_amount'           => '1',
                'discount_qty'              => '0',
                'discount_step'             => '',
                'apply_to_shipping'         => '0',
                'simple_free_shipping'      => '0',
                'stop_rules_processing'     => '0',
                'reward_points_delta'       => '0',
                'store_labels'              => [
                    0 => '',
                    1 => '',
                    2 => '',
                    3 => '',
                    4 => '',
                    5 => '',
                    6 => '',
                    7 => '',
                    8 => '',
                    9 => '',
                    10 => '',
                    11 => '',
                ],
                'page'                      => '1',
                'limit'                     => '20',
                'in_banners'                => '',
                'banner_id'                 => [
                    'from'  => '',
                    'to'    => '',
                ],
                'banner_name'               => '',
                'visible_in'                => '',
                'banner_is_enabled'         => '',
                'related_banners'           => [],
            ];
            if (isset($data['simple_action']) && $data['simple_action'] == 'cart_fixed'
                && isset($data['discount_amount'])
            ) {
                $data['discount_amount'] = min(1, $data['discount_amount']);
            }
            if (isset($data['rule']['conditions'])) {
                $data['conditions'] = $data['rule']['conditions'];
            }
            if (isset($data['rule']['actions'])) {
                $data['actions'] = $data['rule']['actions'];
            }
            unset($data['rule']);

            $rule = $ruleFactory->create();
            $rule->loadPost($data);
            $useAutoGeneration = (int)!empty($data['use_auto_generation']);
            $rule->setUseAutoGeneration($useAutoGeneration);
            $rule->save();
        }
    }

    /**
     * {@inheritdoc}
     * @since 2.1.0
     */
    public function getActionTitle()
    {
        return 'Generating customer segments and rules';
    }

    /**
     * {@inheritdoc}
     * @since 2.1.0
     */
    public function introduceParamLabels()
    {
        return [
            'customer_segment_rules' => 'Customer Segments and Rules'
        ];
    }
}
