<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\MessageQueue\UseCase;

use Magento\TestModuleAsyncAmqp\Model\AsyncTestData;

class AsyncSingleQueueTest extends QueueTestCaseAbstract
{
    /**
     * @var AsyncTestData
     */
    protected $msgObject;

    /**
     * @var string
     */
    protected $consumer = 'queueForMultipleTopicsTestC';

    /**
     * {@inheritdoc}
     */
    protected $consumers = ['queueForMultipleTopicsTestC'];

    /**
     * @var int
     */
    protected $maxMessages = 4;

    public function testMaxMessages()
    {
        $this->msgObject = $this->objectManager->create(AsyncTestData::class);

        // Publish asynchronous messages
        foreach (['message1', 'message2', 'message3'] as $item) {
            $this->msgObject->setValue($item);
            $this->msgObject->setTextFilePath($this->logFilePath);
            $this->publisher->publish('multi.topic.queue.topic.c', $this->msgObject);
        }
        $this->waitForAsynchronousResult(3, $this->logFilePath);

        // check that consumer is still running
        $this->assertNotEmpty($this->getConsumerProcessIds($this->consumer), 'Consumer is not running');

        // clear file content
        file_put_contents($this->logFilePath, '');

        // try to read one more message because maximum is 4 and we read only 3
        $this->msgObject->setValue('message4');
        $this->msgObject->setTextFilePath($this->logFilePath);
        $this->publisher->publish('multi.topic.queue.topic.c', $this->msgObject);
        $this->waitForAsynchronousResult(1, $this->logFilePath);

        // check that consumer finished its work
        $this->assertEmpty($this->getConsumerProcessIds($this->consumer), 'Consumer is still running');
    }
}
