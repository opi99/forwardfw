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

namespace ForwardFW\Cache\Backend;

/**
 * Implementation of a File Cache Backend.
 */
class File extends \ForwardFW\Cache\Backend
{
    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     * @param ForwardFW\Config\Cache\Backend $config Backend config.
     */
    public function __construct(
        \ForwardFW\Controller\ApplicationInterface $application,
        \ForwardFW\Config\Cache\Backend $config
    ) {
        parent::__construct($application, $config);
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
    protected function writeData($hash, array $arData)
    {
        $path = $this->config->getPath();
        if (is_writeable($path)) {
            return file_put_contents($path . $hash, serialize($arData));
        } else {
            throw new \ForwardFW\Cache\Exception('Not writeable');
        }
    }

    /**
     * Reads data from the cache
     *
     * @param string $hash Hash for data.
     *
     * @return array Data from the storage
     */
    protected function readData($hash)
    {
        $path = $this->config->getPath();
        if (is_readable($path . $hash)) {
            return unserialize(file_get_contents($path . $hash));
        }
        return null;
    }

    /**
     * Removes data from the cache
     *
     * @param string $hash Hash for data.
     *
     * @return boolean Returns true if data removed otherwise false.
     */
    protected function removeData($hash)
    {
        $path = $this->config->getPath();
        if (is_writeable($path . $hash)) {
            return unlink($path . $hash);
        }
        return false;
    }

    /**
     * Clear complete cache
     *
     * @return void
     */
    protected function clear()
    {
        $arFiles = glob($this->config->getPath());
        foreach ($arFiles as $strFile) {
            unlink($strFile);
        }
    }
}
