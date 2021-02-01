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

namespace ForwardFW\Cache;

/**
 * Implementation of a Cache Backend.
 */
abstract class Backend implements BackendInterface
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Config\Cache\Backend  $config      Backend config.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    ) {
        $this->application = $application;
        $this->config = $config;
    }

    /**
     * Gets data from Cache.
     *
     * @param string $hash Hash for data.
     * @param integer $nTime Oldest Time of data in cache.
     *
     * @return mixed Data from cache
     * @throws ForwardFW\Cache\Exception\IsGenerating
     * @throws ForwardFW\Cache\Exception\TimeOut
     * @throws ForwardFW\Cache\Exception\NoData
     */
    public function getData($hash, $nTime)
    {
        $arData = $this->readData($hash);
        if (!is_null($arData) && is_array($arData)) {
            if ($arData['time'] > $nTime) {
                if (!$arData['generating']) {
                    $this->application->getResponse()->addLog(
                        'Cache Backend: Hit'
                    );
                    return $arData['data'];
                } else {
                    // Data is generating
                    $this->application->getResponse()->addLog(
                        'Cache Backend: Data isGenerating'
                    );
                    throw new \ForwardFW\Cache\Exception\IsGenerating();
                }
            } else {
                // Data but timed out exception
                $this->application->getResponse()->addLog(
                    'Cache Backend: Data timed out'
                );
                throw new \ForwardFW\Cache\Exception\TimeOut();
            }
        } else {
            // No Data Exception
            $this->application->getResponse()->addLog(
                'Cache Backend: No data available'
            );
            throw new \ForwardFW\Cache\Exception\NoData();
        }
    }


    /**
     * Sets data into Cache.
     *
     * @param string $hash Hash for data.
     * @param mixed $data Data to save into cache.
     *
     * @return void
     */
    public function setData($hash, $data)
    {
        $this->writeData(
            $hash,
            array(
                'data' => $data,
                'time' => time(),
                'generating' => false,
            )
        );
    }

    /**
     * Clears data from Cache.
     *
     * @param string $hash Hash for data.
     *
     * @return void
     */
    public function unsetData($hash)
    {
        $this->removeData(
            $hash
        );
    }

    /**
     * Sets marker that cache will be generated yet.
     *
     * @param string $hash Hash of cache which is generated.
     *
     * @return void
     */
    public function setGenerating($hash)
    {
        $this->writeData(
            $hash,
            array(
                'time' => time(),
                'generating' => true,
            )
        );
    }

    /**
     * Writes data into the cache
     *
     *
     * @param string $hash Hash for data.
     * @param array $arData Data to save into cache.
     *
     * @return void
     */
    abstract protected function writeData($hash, array $arData);

    /**
     * Reads data from the cache
     *
     * @param string $hash Hash for data.
     *
     * @return array Data from the storage
     */
    abstract protected function readData($hash);

    /**
     * Removes data from the cache
     *
     * @param string $hash Hash for data.
     *
     * @return boolean Returns true if data removed otherwise false.
     */
    abstract protected function removeData($hash);

    /**
     * Clear complete cache
     *
     * @return void
     */
    abstract protected function clear();
}
