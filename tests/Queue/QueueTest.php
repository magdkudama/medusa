<?php

namespace Medusa\Queue;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Medusa\Queue\QueueIsEmpty
     */
    public function peekOnEmptyShouldThrowException()
    {
        Queue::createEmpty()->peek();
    }

    /**
     * @test
     * @expectedException \Medusa\Queue\QueueIsEmpty
     */
    public function dequeOnEmptyShouldThrowException()
    {
        Queue::createEmpty()->dequeue();
    }

    /**
     * @test
     */
    public function lastInShouldBeFirstOut()
    {
        $q = $this->createQueue(array(1, 2, 3));

        $this->assertEquals(array(1, 2, 3), $this->dequeueValues($q));
    }

    private function createQueue(array $values)
    {
        $q = Queue::createEmpty();
        foreach ($values as $value) {
            $q = $q->enqueue($value);
        }

        return $q;
    }

    private function dequeueValues(Queueable $queue)
    {
        $values = array();
        foreach ($queue as $value) {
            $values[] = $value;
        }

        return $values;
    }

    /**
     * @test
     */
    public function dequeueShouldNotAffectPreviousVersions()
    {
        $q = $this->createQueue(array(1, 2, 3));

        $q->dequeue();

        $this->assertValues(array(1, 2, 3), $q);
    }

    private function assertValues(array $values, Queueable $queue)
    {
        $this->assertEquals($values, $this->dequeueValues($queue));
    }

    /**
     * @test
     */
    public function dequeueShouldRemoveLastElement()
    {
        $q = $this->createQueue(array(1, 2, 3));

        $this->assertValues(array(2, 3), $q->dequeue());
    }

    /**
     * @test
     */
    public function enqueueShouldNotAffectPreviousVersions()
    {
        $s = Queue::createEmpty();

        $s->enqueue(4);

        $this->assertValues(array(), $s);
    }

    /**
     * @test
     */
    public function enqueueShouldAppendValue()
    {
        $s = $this->createQueue(array(1, 2, 3));

        $this->assertValues(array(1, 2, 3, 4), $s->enqueue(4));
    }
}
