<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;

use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Fixture\Product\CategoryIds;

/**
 * Create and return Category.
 */
class CategoryId extends CategoryIds
{
    /**
     * Fixtures of category.
     *
     * @var Category
     */
    protected $category;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param int|string $data
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        array $params,
        $data
    ) {
        $this->params = $params;
        if (!empty($data['dataset'])) {
            $dataset = $data['dataset'];
            $category = $fixtureFactory->createByCode('category', ['dataset' => $dataset]);
            $category->persist();

            /** @var Category $category */
            $this->data = $category->getName();
            $this->category = $category;
        } else {
            $this->data = $data;
        }
    }

    /**
     * Return category.
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}
