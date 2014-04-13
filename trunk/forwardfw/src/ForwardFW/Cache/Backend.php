<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * PHP version 5
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Cache
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.8
 */

namespace ForwardFW\Cache;

/**
 * Implementation of a Cache Backend.
 *
 * @category   Cache
 * @package    ForwardFW
 * @subpackage Cache
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
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
     * @param string  $strHash Hash for data.
     * @param integer $nTime   Oldest Time of data in cache.
     *
     * @return mixed Data from cache
     * @throws ForwardFW\Cache\Exception\IsGenerating
     * @throws ForwardFW\Cache\Exception\TimeOut
     * @throws ForwardFW\Cache\Exception\NoData
     */
    public function getData($strHash, $nTime)
    {
        $arData = $this->readData($strHash);
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
     * @param string $strHash Hash for data.
     * @param mixed  $mData   Data to save into cache.
     *
     * @return void
     */
    public function setData($strHash, $mData)
    {
        $this->writeData(
            $strHash,
            array(
                'data' => $mData,
                'time' => time(),
                'generating' => false,
            )
        );
    }

    /**
     * Clears data from Cache.
     *
     * @param string $strHash Hash for data.
     *
     * @return void
     */
    public function unsetData($strHash)
    {
        $this->removeData(
            $strHash
        );
    }

    /**
     * Sets marker that cache will be generated yet.
     *
     * @param string $strHash Hash of cache which is generated.
     *
     * @return void
     */
    public function setGenerating($strHash)
    {
        $this->writeData(
            $strHash,
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
     * @param string $strHash Hash for data.
     * @param array  $arData  Data to save into cache.
     *
     * @return void
     */
    abstract protected function writeData($strHash, array $arData);

    /**
     * Reads data from the cache
     *
     * @param string $strHash Hash for data.
     *
     * @return array Data from the storage
     */
    abstract protected function readData($strHash);

    /**
     * Removes data from the cache
     *
     * @param string $strHash Hash for data.
     *
     * @return boolean Returns true if data removed otherwise false.
     */
    abstract protected function removeData($strHash);

    /**
     * Clear complete cache
     *
     * @return void
     */
    abstract protected function clear();
}
