<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Fixture\Address;

use Magento\Catalog\Test\Fixture\CatalogProductAttribute;
use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Source for attribute field.
 */
class CustomAttribute extends DataSource
{
    /**
     * Attribute fixture.
     *
     * @var CatalogProductAttribute
     */
    private $attribute;

    /**
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * @var mixed
     */
    private $fixtureData;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param mixed $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, $data)
    {
        $this->params = $params;
        $this->fixtureData = $data;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * @param null $key
     * @return mixed
     * @throws \Exception
     */
    public function getData($key = null)
    {
        if (!isset($this->fixtureData['dataset'])) {
            throw new \Exception("Data must be set");
        }
        $attribute = $this->fixtureFactory->createByCode(
            'customerAddressAttribute',
            ['dataset' => $this->fixtureData['dataset']]
        );
        $attribute->persist();
        $this->data['code'] = $attribute->getAttributeCode();
        $this->data['value'] = $this->fixtureData['value'];
        $this->data['type'] = 'input';
        $this->attribute = $attribute;

        return parent::getData($key);
    }

    /**
     * Return CatalogProductAttribute fixture.
     *
     * @return CatalogProductAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
}
