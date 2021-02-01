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

namespace ForwardFW\Controller;

/**
 * Factory(?) Holder of DataHandler handlers.
 */
class DataHandler extends \ForwardFW\Service\AbstractService implements DataHandlerInterface
{
    /**
     * @var array Cache of connections
     */
    protected $connectionCache = array();

    public function __construct(\ForwardFW\ServiceManager $serviceManager, \ForwardFW\Config\Service $config)
    {
        if ($config instanceof \ForwardFW\Config\Service\DataHandler) {
            parent::__construct($serviceManager, $config);
        } else {
            throw new \Exception('Not my config class');
        }
    }
    /**
     * Returns an instance of configured DataHandler.
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
// No instancing DataHandler is a service now.
//     public static function getInstance(ApplicationInterface $application)
//     {
//         if (isset($GLOBALS['DataLoader']['instance'][$application->getName()])) {
//             $return = $GLOBALS['DataLoader']['instance'][$application->getName()];
//         } else {
//             $return = new self($application);
//             $GLOBALS['DataLoader']['instance'][$application->getName()] = $return;
//         }
//         return $return;
//     }

    /**
     * Loads Data from cache or from a connection (DB, SOAP, File) if cache failed.
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFromCached($connectionName, array $options, $nCacheTimeout = -1)
    {
        $handler = $this->getConnection($connectionName);

        $cache = $this->getCacheSystem($connectionName);

        $cacheCallback = new \ForwardFW\Callback(
            array($handler, 'loadFrom'),
            array($connectionName, $options)
        );

        $configCacheData = new \ForwardFW\Config\Cache\Data\Caller();
        $configCacheData
            ->setCallback($cacheCallback)
            ->setTimeout($nCacheTimeout);

        return $cache->getCache($configCacheData);
    }


    /**
     * Initializes and returns the caching system depending on connection
     * configuration.
     *
     * @param string $connectionName Name of connection
     *
     * @return ForwardFW_Cache_Frontend The Cache Frontend.
     */
    protected function getCacheSystem($connectionName)
    {
        $backendConfig = new \ForwardFW\Config\Cache\Backend\File();
        $backendConfig->strPath = getcwd() . '/cache/';

        $configCacheFrontend = new \ForwardFW\Config\Cache\Frontend\Caller();
        $configCacheFrontend->setBackendConfig($backendConfig);

        $cache = \ForwardFW\Cache\Frontend::getInstance(
            $this->application,
            $configCacheFrontend
        );

        return $cache;
    }

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom($connectionName, array $options)
    {
        $handler = $this->getConnection($connectionName);
        return $handler->loadFrom($connectionName, $options);
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     * @param ForwardFW\Callback $idCallback Callback to give id of object creation
     *
     * @return mixed Data from the connection.
     */
    public function create($connectionName, array $options, \ForwardFW\Callback $idCallback = null)
    {
        $handler = $this->getConnection($connectionName);
        return $handler->create($connectionName, $options, $idCallback);
    }

    /**
     * Truncates Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function truncate($connectionName, array $options)
    {
        $handler = $this->getConnection($connectionName);
        return $handler->truncate($connectionName, $options);
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo($connectionName, array $options)
    {
        $handler = $this->getConnection($connectionName);
        return $handler->saveTo($connectionName, $options);
    }

    /**
     * Gets the connection handler.
     *
     * @param string $connectionName Name of connection
     *
     * @return mixed ConnectionHandler
     */
    protected function getConnection($connectionName)
    {
        if (!isset($this->connectionCache[$connectionName])) {
            $this->initConnection($connectionName);
        }
        // Return existing connection
        return $this->connectionCache[$connectionName];
    }

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $connectionName Name of connection
     *
     * @return void
     */
    public function initConnection($connectionName)
    {
        $config = $this->getConfigParameter($connectionName);
        $handlerClassName = $config['handler'];

        if (class_exists($handlerClassName)) {
            $handler = new $handlerClassName($this->application);
        } else {
            throw new \ForwardFW\Exception\DataHandler('DataHandlerClass "' . $handlerClassName . '" not includeable.');
        }

        $this->connectionCache[$connectionName] = $handler;
    }

    /**
     * Quotes and escapes a string to be save in SQL query.
     *
     * @param string $connectionName Name of connection
     * @param string $value The string to be quoted savely
     *
     * @return string
     */
    public function quoteString($connectionName, $value)
    {
        $handler = $this->getConnection($connectionName);
        return $handler->quoteString($connectionName, $value);
    }
}
