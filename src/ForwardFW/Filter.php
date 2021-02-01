<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace ForwardFW;

/**
 * This abstract class needs to be extended to be a callable filter.
 */
abstract class Filter
{
    /**
     * Child filter that should be executed after your Incoming process.
     *
     * @var ForwardFW_Filter
     */
    protected $child = null;

    /**
     * Set to true if the Chain should stop here.
     *
     * @var boolean
     */
    protected $doStopChain = false;

    /**
     * Constructor
     *
     * @param ForwardFW\Filter $child The child filter or null if you are the last
     *
     * @return new instance
     */
    public function __construct(Filter $child = null)
    {
        $this->child = $child;
    }

    /**
     * Function to process before your child
     *
     * @return void
     */
    abstract public function doIncomingFilter();

    /**
     * Function to process after your child
     *
     * @return void
     */
    abstract public function doOutgoingFilter();

    /**
     * Sets a child for the filter.
     *
     * @param ForwardFW\Filter $child The child filter or null if you are the last
     *
     * @return void
     */
    public function setChild(Filter $child = null)
    {
        $this->child = $child;
    }

    /**
     * Function to process filtering incoming/child/outgoing
     *
     * @return void
     */
    public function doFilter()
    {
        $this->doIncomingFilter();
        if (!is_null($this->child) && !$this->doStopChain) {
            $this->child->doFilter();
        }
        $this->doOutgoingFilter();
    }
}
