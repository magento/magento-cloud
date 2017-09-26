<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Setup\Fixtures;

/**
 * Class TargetRulesFixture
 */
class TargetRulesFixture extends Fixture
{
    /**
     * @var int
     */
    protected $priority = 95;

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD)
     */
    public function execute()
    {
        $catalogTargetRules = $this->fixtureModel->getValue('catalog_target_rules', 0);
        if (!$catalogTargetRules) {
            return;
        }
        $this->fixtureModel->resetObjectManager();

        /** @var \Magento\Store\Model\StoreManager $storeManager */
        $storeManager = $this->fixtureModel->getObjectManager()->create(\Magento\Store\Model\StoreManager::class);
        /** @var $category \Magento\Catalog\Model\Category */
        $category = $this->fixtureModel->getObjectManager()->get(\Magento\Catalog\Model\Category::class);
        /** @var $model  \Magento\TargetRule\Model\Rule*/
        $model = $this->fixtureModel->getObjectManager()->get(\Magento\TargetRule\Model\Rule::class);
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
        $idField = $model->getIdFieldName();

        for ($i = 0; $i < $catalogTargetRules; $i++) {
            //Necessary to create the correct data in magento_targetrule_product table
            $this->fixtureModel->resetObjectManager();
            $model = $this->fixtureModel->getObjectManager()->get(\Magento\TargetRule\Model\Rule::class);
            //------------

            $ruleName = sprintf('Catalog Target Rule %1$d', $i);
            $data = [
                $idField                => null,
                'name'                  => $ruleName,
                'sort_order'            => 0,
                'is_active'             => 1,
                'apply_to'              => 1,
                'from_date'             => '',
                'to_date'               => '',
                'positions_limit'       => 10,
                'use_customer_segment'  => 0,
                'customer_segment_ids'  => '',
                'rule'                  => [
                    'conditions' => [
                        1 => [
                            'type' => \Magento\TargetRule\Model\Rule\Condition\Combine::class,
                            'aggregator' => 'all',
                            'value' => '1',
                            'new_child' => '',
                        ],
                        '1--1' => [
                            'type' => \Magento\TargetRule\Model\Rule\Condition\Product\Attributes::class,
                            'attribute' => 'category_ids',
                            'operator' => '==',
                            'value' => $categoriesArray[$i % count($categoriesArray)][0],
                        ],
                    ],
                    'actions' => [
                        1 => [
                            'type' => \Magento\TargetRule\Model\Actions\Condition\Combine::class,
                            'aggregator' => 'all',
                            'value' => '1',
                            'new_child' => '',
                        ],
                        '1--1' => [
                            'type' => \Magento\TargetRule\Model\Actions\Condition\Product\Attributes::class,
                            'attribute' => 'category_ids',
                            'operator' => '==',
                            'value_type' => 'same_as',
                            'value' => '',
                        ],
                    ],
                ],
            ];
            if (isset($data['rule']['conditions'])) {
                $data['conditions'] = $data['rule']['conditions'];
            }
            if (isset($data['rule']['actions'])) {
                $data['actions'] = $data['rule']['actions'];
            }
            unset($data['rule']);

            $model->loadPost($data);
            $model->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActionTitle()
    {
        return 'Generating target rules';
    }

    /**
     * {@inheritdoc}
     */
    public function introduceParamLabels()
    {
        return [
            'catalog_target_rules' => 'Catalog Target Rules'
        ];
    }
}
