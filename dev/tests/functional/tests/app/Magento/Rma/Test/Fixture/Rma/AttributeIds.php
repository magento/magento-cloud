<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Fixture\Rma;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Rma\Test\Fixture\RmaAttribute;

/**
 * Prepare rma attributes.
 */
class AttributeIds extends DataSource
{
    /**
     * Rma attributes.
     *
     * @var array
     */
    private $rmaAttributes;

    /**
     * Fixture Factory instance.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Rough fixture field data.
     *
     * @var array
     */
    private $fixtureData;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data = [])
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->params = $params;
        $this->fixtureData = $data;
    }

    /**
     * Return prepared data set.
     *
     * @param string $key [optional]
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData($key = null)
    {
        if (empty($this->fixtureData)) {
            throw new \Exception("Data must be set");
        }

        if (isset($this->fixtureData['dataset'])) {
            $datasets = explode(',', $this->fixtureData['dataset']);

            foreach ($datasets as $dataset) {
                /** @var RmaAttribute $rmaAttribute */
                $rmaAttribute = $this->fixtureFactory->createByCode('rmaAttribute', ['dataset' => $dataset]);

                if (!$rmaAttribute->getAttributeId()) {
                    $rmaAttribute->persist();
                }

                $this->data[] = $rmaAttribute->getAttributeId();
                $this->rmaAttributes[] = $rmaAttribute;
            }
        }

        return parent::getData($key);
    }

    /**
     * Return rma attributes.
     *
     * @return array
     */
    public function getRmaAttributes()
    {
        return $this->rmaAttributes;
    }
}
