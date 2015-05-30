<?php

namespace Port\Step;

use Port\Step;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
interface PriorityStep extends Step
{
    /**
     * @return integer
     */
    public function getPriority();
}
