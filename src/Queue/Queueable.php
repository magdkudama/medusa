<?php

namespace Medusa\Queue;

interface Queueable extends \Traversable
{
    public function isEmpty();

    public function peek();

    public function enqueue($value);

    public function dequeue();
}