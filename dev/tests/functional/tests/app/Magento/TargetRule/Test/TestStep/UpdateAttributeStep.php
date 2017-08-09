<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\TargetRule\Test\TestStep;

use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductAttributeIndex;
use Magento\Catalog\Test\Page\Adminhtml\CatalogProductAttributeNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Class UpdateAttributeStep update product attribute data.
 */
class UpdateAttributeStep implements TestStepInterface
{
    /**
     * Factory for creating attribute fixture.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Product attribute grid page.
     *
     * @var CatalogProductAttributeIndex
     */
    private $attributeIndex;

    /**
     * Product attribute edit page.
     *
     * @var CatalogProductAttributeNew
     */
    private $attributeNew;

    /**
     * Attribute code.
     *
     * @var string
     */
    private $attributeCode;

    /**
     * Update attribute data.
     *
     * @var array
     */
    private $updateAttributeData;

    /**
     * Product attribute data before update.
     *
     * @var array
     */
    private $origAttributeData = [];

    /**
     * UpdateAttributeStep constructor.
     *
     * @param FixtureFactory $fixtureFactory
     * @param CatalogProductAttributeIndex $attributeIndex
     * @param CatalogProductAttributeNew $attributeNew
     * @param string $attributeCode
     * @param array $updateAttributeData
     */
    public function __construct(
        FixtureFactory $fixtureFactory,
        CatalogProductAttributeIndex $attributeIndex,
        CatalogProductAttributeNew $attributeNew,
        $attributeCode,
        $updateAttributeData
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->attributeIndex = $attributeIndex;
        $this->attributeNew = $attributeNew;
        $this->attributeCode = $attributeCode;
        $this->updateAttributeData = $updateAttributeData;
    }

    /**
     * Update attribute data.
     *
     * @return void
     */
    public function run()
    {
        $this->updateAttribute($this->createAttribute($this->updateAttributeData));
    }

    /**
     * Revert update product attribute.
     *
     * @return void
     */
    public function cleanup()
    {
        if ($this->origAttributeData) {
            $attribute = $this->createAttribute($this->origAttributeData);
            $this->updateAttribute($attribute);
            $this->origAttributeData = [];
        }
    }

    /**
     * Create product attribute fixture.
     *
     * @param $data
     * @return CatalogProductAttribute
     */
    private function createAttribute($data)
    {
        $attribute = $this->fixtureFactory->createByCode(
            'catalogProductAttribute',
            [
                'data' => $data,
            ]
        );
        return $attribute;
    }

    /**
     * Update product attribute.
     *
     * @param $attribute
     * @return void
     */
    private function updateAttribute($attribute)
    {
        $this->attributeIndex->open();
        $this->attributeIndex->getGrid()->searchAndOpen(['attribute_code' => $this->attributeCode]);
        $this->origAttributeData = $this->attributeNew->getAttributeForm()->getData($attribute);
        $this->attributeNew->getAttributeForm()->fill($attribute);
        $this->attributeNew->getPageActions()->save();
    }
}
