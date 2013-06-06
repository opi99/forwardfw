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
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2013 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.7
 */

namespace ForwardFW\Controller;

/**
 * Managing DataLoading via PEAR::MDB
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class DataHandler extends \ForwardFW\Controller implements DataHandlerInterface
{
    /**
     * @var array Cache of connections
     */
    protected $arConnectionCache = array();

    /**
     * Constructor
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
    public function __construct(ApplicationInterface $application)
    {
        parent::__construct($application);
    }

    /**
     * Returns an instance of configured DataHandler.
     *
     * @param ForwardFW\Controller\ApplicationInterface $application The running application.
     *
     * @return void
     */
    public static function getInstance(ApplicationInterface $application)
    {
        if (isset($GLOBALS['DataLoader']['instance'][$application])) {
            $return = $GLOBALS['DataLoader']['instance'][$application->getName()];
        } else {
            $return = new self($application);
            $GLOBALS['DataLoader']['instance'][$application->getName()] = $return;
        }
        return $return;
    }

    /**
     * Loads Data from cache or from a connection (DB, SOAP, File) if cache failed.
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFromCached($strConnection, array $arOptions, $nCacheTimeout = -1)
    {
        $handler = $this->getConnection($strConnection);

        $cache = $this->getCacheSystem($strConnection);

        $cacheCallback = new \ForwardFW\Callback(
            array($handler, 'loadFrom'),
            array($strConnection, $arOptions)
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
     * @param string $strConnection Name of connection
     *
     * @return ForwardFW_Cache_Frontend The Cache Frontend.
     */
    protected function getCacheSystem($strConnection)
    {
        $backendConfig = new \ForwardFW\Config\Cache\Backend\File();
        $backendConfig->strPath = getcwd() . '/cache/';

        $configCacheFrontend = new \ForwardFW\Config\Cache\Frontend();
        $configCacheFrontend
            ->setCacheBackend('ForwardFW\\Cache\\Backend\\File')
            ->setBackendConfig($backendConfig)
            ->setCacheFrontend('ForwardFW\\Cache\\Frontend\\Caller');

        $cache = \ForwardFW\Cache\Frontend::getInstance(
            $this->application,
            $configCacheFrontend
        );

        return $cache;
    }

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function loadFrom($strConnection, array $arOptions)
    {
        $handler = $this->getConnection($strConnection);
        return $handler->loadFrom($strConnection, $arOptions);
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return mixed Data from the connection.
     */
    public function saveTo($strConnection, array $options)
    {
        $handler = $this->getConnection($strConnection);
        return $handler->saveTo($strConnection, $arOptions);
    }


    /**
     * Gets the connection handler.
     *
     * @param string $strConnection Name of connection
     *
     * @return mixed ConnectionHandler
     */
    protected function getConnection($strConnection)
    {
        if (!isset($this->arConnectionCache[$strConnection])) {
            $this->initConnection($strConnection);
        }
        // Return existing connection
        return $this->arConnectionCache[$strConnection];
    }

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $strConnection Name of connection
     *
     * @return void
     */
    public function initConnection($strConnection)
    {
        $arConfig = $this->getConfigParameter($strConnection);
        $strHandler = $arConfig['handler'];

        $strFile = str_replace('\\', '/', $strHandler) . '.php';

        $rIncludeFile = @fopen($strFile, 'r', true);
        if ($rIncludeFile) {
            fclose($rIncludeFile);
            $ret = include_once $strFile;
            if (!$ret) {
                $this->application->getResponse()->addError('DataHandler not includeable.');
            } else {
                $handler= new $strHandler($this->application);
            }
        } else {
            $this->application->getResponse()->addError(
                'DataHandler Controller File "'.htmlspecialchars($strFile).'" not found'
            );
        }

        $this->arConnectionCache[$strConnection] = $handler;
    }
}