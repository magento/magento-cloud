<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\ForeignKey;

use Magento\Framework\ForeignKey\StrategyInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ForeignKeyResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface
     */
    protected $transactionManager;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\ObjectRelationProcessor
     */
    protected $relationProcessor;

    public static function setUpBeforeClass()
    {
        include __DIR__ . '/_files/entity_tables.php';
    }

    public static function tearDownAfterClass()
    {
        include __DIR__ . '/_files/entity_tables_rollback.php';
    }

    protected function setUp()
    {
        include __DIR__ . '/_files/entity_tables_values.php';
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\Framework\App\ResourceConnection $resource */
        $resource = $this->objectManager->get(\Magento\Framework\App\ResourceConnection::class);
        $this->connection = $resource->getConnection('default');
        $this->relationProcessor = $this->objectManager->create(
            \Magento\Framework\Model\ResourceModel\Db\ObjectRelationProcessor::class
        );
    }

    protected function tearDown()
    {
        include __DIR__ . '/_files/entity_tables_values_rollback.php';
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDeleteRestrict()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        try {
            $this->invokeDeleteAction(StrategyInterface::TYPE_RESTRICT, 'test_entity_one');
            $this->fail('Expected Foreign Key exception.');
        } catch (LocalizedException $exception) {
            $this->assertEquals(
                'Cannot add or update a child row: a foreign key constraint fails',
                $exception->getMessage()
            );

            $select = $this->connection->select()->from($this->connection->getTableName('test_entity_one'));
            $data = $this->connection->fetchAll($select);
            $this->assertGreaterThan(0, count($data), 'Invalid items count in: test_entity_one');

            $select = $this->connection->select()->from($this->connection->getTableName('test_entity_two'));
            $data = $this->connection->fetchAll($select);
            $this->assertGreaterThan(0, count($data), 'Invalid items count in: test_entity_two');

            $select = $this->connection->select()->from($this->connection->getTableName('test_entity_three'));
            $data = $this->connection->fetchAll($select);
            $this->assertGreaterThan(0, count($data), 'Invalid items count in: test_entity_three');

            $select = $this->connection->select()->from($this->connection->getTableName('test_entity_four'));
            $data = $this->connection->fetchAll($select);
            $this->assertGreaterThan(0, count($data), 'Invalid items count in: test_entity_four');
        }
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDeleteCascade()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        $this->invokeDeleteAction(StrategyInterface::TYPE_CASCADE, 'test_entity_one');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_two'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_two');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_three'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_three');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_four'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_four');
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDeleteCascadeThroughParent()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        $this->invokeDeleteAction(StrategyInterface::TYPE_CASCADE, 'base_test_entity');

        $select = $this->connection->select()->from($this->connection->getTableName('parent_of_test_entity_one'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_one');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_two'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_two');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_three'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_three');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_four'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_four');
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDeleteCascadeThroughSuperParent()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        $this->invokeDeleteAction(StrategyInterface::TYPE_CASCADE, 'base_test_entity');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_one'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_one');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_two'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_two');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_three'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_three');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_four'));
        $data = $this->connection->fetchAll($select);
        $this->assertCount(0, $data, 'Invalid items count in: test_entity_four');
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDeleteSetNull()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        $this->invokeDeleteAction(StrategyInterface::TYPE_SET_NULL, 'test_entity_one');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_two'));
        $data = $this->connection->fetchAll($select);

        foreach ($data as $item) {
            $this->assertNull($item['reference_col'], 'Invalid SET NULL logic');
        }

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_three'));
        $data = $this->connection->fetchAll($select);
        $this->assertGreaterThan(0, count($data), 'Invalid items count in: test_entity_three');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_four'));
        $data = $this->connection->fetchAll($select);
        $this->assertGreaterThan(0, count($data), 'Invalid items count in: test_entity_four');
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testDeleteMixedStrategy()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        $this->invokeDeleteAction('MIXED', 'test_entity_one');

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_two'));
        $data = $this->connection->fetchAll($select);
        $this->assertEquals(0, count($data));

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_three'));
        $data = $this->connection->fetchAll($select);

        foreach ($data as $item) {
            $this->assertNull($item['reference_col'], 'Invalid SET NULL logic');
        }

        $select = $this->connection->select()->from($this->connection->getTableName('test_entity_four'));
        $data = $this->connection->fetchAll($select);
        $this->assertGreaterThan(0, count($data));
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testSaveForeignKeyValidation()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        //valid reference id
        $referenceFieldId = 1;
        $this->invokeValidation($referenceFieldId);
    }

    /**
     * @magentoAppIsolation enabled
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Cannot update row: a foreign key constraint fails
     */
    public function testSaveForeignKeyValidationThrowException()
    {
        $this->markTestSkipped('No stable behaviour in parallel run mode.');
        //invalid reference id
        $referenceFieldId = 1000;
        $this->invokeValidation($referenceFieldId);
    }

    /**
     * @param int $referenceId
     */
    protected function invokeValidation($referenceId)
    {
        $strategy = $this->getConfig(StrategyInterface::TYPE_CASCADE);
        $this->objectManager->addSharedInstance($strategy, \Magento\Framework\ForeignKey\ConfigInterface::class);
        $this->objectManager->addSharedInstance($strategy, \Magento\Framework\ForeignKey\Config::class);

        $this->relationProcessor->validateDataIntegrity(
            $this->connection->getTableName('test_entity_two'),
            ['reference_col' => $referenceId]
        );
    }

    /**
     * @param string $strategy
     * @param string $table
     * @throws \Exception
     */
    protected function invokeDeleteAction($strategy, $table)
    {
        $strategy = $this->getConfig($strategy);
        $this->objectManager->addSharedInstance($strategy, \Magento\Framework\ForeignKey\ConfigInterface::class);
        $this->objectManager->addSharedInstance($strategy, \Magento\Framework\ForeignKey\Config::class);

        $transactionManager = $this->objectManager->create(
            \Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface::class
        );

        $transactionManager->start($this->connection);
        try {
            $this->relationProcessor->delete(
                $transactionManager,
                $this->connection,
                $this->connection->getTableName($table),
                'entity_id = 1',
                ['entity_id' => 1]
            );
            $transactionManager->commit();
        } catch (\Exception $e) {
            $transactionManager->rollBack();
            throw $e;
        }
    }

    /**
     * @param $strategy
     * @return \Magento\Framework\ForeignKey\ConfigInterface
     */
    protected function getConfig($strategy)
    {
        $configPath = __DIR__ . '/etc/constraints_' . str_replace(' ', '_', strtolower($strategy)) . '.xml';

        $fileResolverMock = $this->createMock(\Magento\Framework\Config\FileResolverInterface::class);
        $fileResolverMock->expects($this->any())
            ->method('get')
            ->willReturn([$configPath => file_get_contents(($configPath))]);

        /** @var \Magento\Framework\ForeignKey\Config\Reader $reader */
        $reader = $this->objectManager->create(
            \Magento\Framework\ForeignKey\Config\Reader::class,
            ['fileResolver' => $fileResolverMock]
        );

        /** @var \Magento\Framework\ForeignKey\Config\Data $dataContainer */
        $dataContainer = $this->objectManager->create(
            \Magento\Framework\ForeignKey\Config\Data::class,
            ['reader' => $reader]
        );
        $dataContainer->reset();

        /** @var \Magento\Framework\ForeignKey\ConfigInterface $config */
        $config = $this->objectManager->create(
            \Magento\Framework\ForeignKey\ConfigInterface::class,
            ['dataContainer' => $dataContainer]
        );
        return $config;
    }
}
