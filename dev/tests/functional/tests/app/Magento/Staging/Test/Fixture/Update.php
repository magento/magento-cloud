<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Fixture;

/**
 * Class Update
 */
class Update extends \Magento\Mtf\Fixture\InjectableFixture
{
    /**
     * @var string
     */
    protected $repositoryClass = 'Magento\Staging\Test\Repository\Update';

    /**
     * @var string
     */
    protected $handlerInterface = 'Magento\Staging\Test\Handler\Update\UpdateInterface';

    /**
     * @var array
     */
    protected $id = [
        'is_required' => '1',
    ];

    /**
     * @var array
     */
    protected $start_time = [
        'group' => 'general',
        'is_required' => '0',
        'source' => 'Magento\Backend\Test\Fixture\Source\Date',
    ];

    /**
     * @var array
     */
    protected $name = [
        'group' => 'general',
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $description = [
        'group' => 'general',
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $rollback_id = [
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $is_campaign = [
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $is_rollback = [
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $moved_to = [
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $end_time = [
        'group' => 'general',
        'is_required' => '0',
        'source' => 'Magento\Backend\Test\Fixture\Source\Date',
    ];

    /**
     * @var array
     */
    protected $entity_type = [
        'is_required' => '0',
    ];

    /**
     * @var array
     */
    protected $product = [
        'is_required' => '0',
        'source' => 'Magento\CatalogStaging\Test\Fixture\Update\Product',
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->getData('start_time');
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * @return mixed
     */
    public function getRollbackId()
    {
        return $this->getData('rollback_id');
    }

    /**
     * @return mixed
     */
    public function getIsCampaign()
    {
        return $this->getData('is_campaign');
    }

    /**
     * @return mixed
     */
    public function getIsRollback()
    {
        return $this->getData('is_rollback');
    }

    /**
     * @return mixed
     */
    public function getMovedTo()
    {
        return $this->getData('moved_to');
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->getData('end_time');
    }

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->getData('entity_type');
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->getData('product');
    }
}
