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
 * @subpackage Controller/DataHandler
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2014-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Controller\DataHandler;

/**
 * Managing DataLoading via SQLite v3
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller/DataHandler
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Sqlite3 extends \ForwardFW\Controller\DataHandler
{
    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Data from the connection.
     */
    public function loadFrom($connectionName, array $options)
    {
        $connection = $this->getConnection($connectionName);

        $strQuery = 'SELECT ' . $options['select'] . ' FROM ' . $this->getTableName($options['to'], $connectionName);
        if (isset($options['where'])) {
            $strQuery .= ' WHERE ' . $options['where'];
        }
        if (isset($options['group'])) {
            $strQuery .= ' GROUP BY ' . $options['group'];
        }
        if (isset($options['order'])) {
            $strQuery .= ' ORDER BY ' . $options['order'];
        }
        if (isset($options['limit'])) {
            $strQuery .= ' LIMIT ' . $options['limit'];
        }

        $arResult = array();

        $result = $connection->query($strQuery);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        while ($arRow = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($arResult, $arRow);
        }
        return $arResult;
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Empty array
     */
    public function saveTo($connectionName, array $options)
    {
        $connection = $this->getConnection($connectionName);

        $strQuery = 'UPDATE ' . $this->getTableName($options['to'], $connectionName);

        $strQuery .= ' SET ';
        foreach ($options['values'] as $strName => $value) {
            $arSets[] = $strName . '=' . $this->getSqlValue($options['columns'][$strName], $value, $connection);
        }
        $strQuery .= implode(',', $arSets);

        $strQuery .= ' WHERE ' . $options['where'];

        $arResult = array();
        $result = $connection->exec($strQuery);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return $arResult;
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     * @param ForwardFW\Callback $idCallback Callback to give id of object creation
     *
     * @return array Empty array
     */
    public function create($connectionName, array $options, \ForwardFW\Callback $idCallback = null)
    {
        $connection = $this->getConnection($connectionName);

        $strQuery = 'INSERT INTO ' . $this->getTableName($options['to'], $connectionName);

        $strQuery .= ' (' . implode(',', array_keys($options['values'])) . ')';
        $strQuery .= ' VALUES (';
        $arValues = array();
        foreach ($options['values'] as $strName => $value) {
            $arValues[] = $this->getSqlValue($options['columns'][$strName], $value, $connection);
        }
        $strQuery .= implode(',', $arValues) . ')';

        $arResult = array();
        $result = $connection->exec($strQuery);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }

        // @TODO Callback with new ID

        return $arResult;
    }

    /**
     * Truncates Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Empty array
     */
    public function truncate($connectionName, array $options)
    {
        $connection = $this->getConnection($connectionName);

        $table = $this->getTableName($options['table'], $connectionName);

        // Delete content of table
        $result = $connection->exec('DELETE FROM ' . $table);

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }

        // Reset auto_inc counter
        $result = $connection->exec('DELETE FROM SQLITE_SEQUENCE WHERE name = \'' . $table . '\'');

        if ($result === false) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return array();
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
        try {
            $connection = new \SQLite3($this->config->getDsn(), SQLITE3_OPEN_READWRITE);
        } catch (\Exception $e) {
            $this->application->getResponse()->addError(
                $e->getMessage()
            );
            throw new \ForwardFW\Exception\DataHandler(
                'Cannot initialize SQLite Connection: '
                . $e->getMessage()
            );
        }

        $ret = $connection->exec('PRAGMA encoding = "utf-8"');
        $connection->busyTimeout(5000);
        $this->arConnectionCache[$connectionName] = $connection;
    }

    public function getSqlValue($strType, $value, $connection)
    {
        if ($strType === 'integer') {
            return (int) $value;
        }
        return '\'' . $connection->escapeString($value) . '\'';
    }

    /**
     * Returns real table name Prefix or DB dependent changes.
     *
     * @param string $tableName Name of table inside application
     * @param string $connectionName Name of connection
     *
     * @return string Name of table inside DB
     */
    protected function getTableName($tableName, $connectionName)
    {
        $tablePrefix = $this->config->getTablePrefix();

        if ($tablePrefix !== '') {
            return $tablePrefix . '_' . $tableName;
        } else {
            return $tableName;
        }
    }
}
