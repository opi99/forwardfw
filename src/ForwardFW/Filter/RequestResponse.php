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

namespace ForwardFW\Filter;

/**
 * This abstract class needs to be extended to be a callable filter.
 */
class RequestResponse extends \ForwardFW\Filter
{
    /**
     * @var ForwardFW\Request The Request object
     */
    protected $request = null;

    /**
     * @var ForwardFW\Request The Response object
     */
    protected $response = null;

    /**
     * @var ForwardFW\ServiceManager The ServiceManager object
     */
    protected $serviceManager = null;

    /**
     * @var ForwardFW\Config\Filter\RequestResponse config of the filter
     */
    protected $config = null;

    /**
     * Constructor
     *
     * @param \ForwardFW\Filter\RequestResponse        $child          The child filter or null if you are the last
     * @param \ForwardFW\Config\Filter\RequestResponse $config         Config for this filter
     * @param \ForwardFW\Request                       $request        The request for this application
     * @param \ForwardFW\Response                      $response       The response for this application
     * @param \ForwardFW\Service                       $serviceManager The services for this application
     *
     * @return new instance
     */
    public function __construct(
        RequestResponse $child = null,
        \ForwardFW\Config\Filter\RequestResponse $config = null,
        \ForwardFW\Request $request = null,
        \ForwardFW\Response $response = null,
        \ForwardFW\ServiceManager $serviceManager = null
    ) {
        parent::__construct($child);
        if (! is_null($this->child)) {
            $this->request  = $this->child->getRequest();
            $this->response = $this->child->getResponse();
            $this->serviceManager = $this->child->getServiceManager();
        } else {
            $this->request  = $request;
            $this->response = $response;
            $this->serviceManager = $serviceManager;
        }
        $this->config = $config;
    }

    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Hallo');
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Bye');
    }

    /**
     * Returns the request object of this process
     *
     * @return ForwardFW_Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the response object of this process
     *
     * @return ForwardFW_Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns the response object of this process
     *
     * @return ForwardFW_Response
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Builds the Filters which are defined in the configuration for RequestResponse
     * handling.
     *
     * @param ForwardFW\Request $request The request for this application
     * @param ForwardFW\Response $response The response for this application
     * @param ForwardFW\ServiceManager $serviceManager The service manager for this application
     *
     * @return \ForwardFW\Filter\RequestResponse The start filter with the
     * configured childs. So the filters can be started.
     */
    public static function getFilters(
        \ForwardFW\Request $request,
        \ForwardFW\Response $response,
        \ForwardFW\ServiceManager $serviceManager,
        \ArrayObject $processors
    ) {
        $filter = null;

        $processors = array_reverse($processors->getArrayCopy());
        foreach ($processors as $config) {
            $strFilterClass = $config->getExecutionClassName();
            $filter = new $strFilterClass($filter, $config, $request, $response, $serviceManager);
        }

        return $filter;
    }
}
