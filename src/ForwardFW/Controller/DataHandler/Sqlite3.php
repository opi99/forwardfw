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
 * @copyright  2014 The Authors
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
     * @var array Prefix for tables
     */
    private $arTablePrefix = array();

    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return array Data from the connection.
     */
    public function loadFrom($strConnection, array $arOptions)
    {
        $connection = $this->getConnection($strConnection);

        $strQuery = 'SELECT ' . $arOptions['select'] . ' FROM ';
        if (isset($this->arTablePrefix[$strConnection])) {
            $strQuery .= '`' . $this->arTablePrefix[$strConnection]
                .'_' . $arOptions['from'] . '`';
        } else {
            $strQuery .= '`' . $arOptions['from'] . '`';
        }
        if (isset($arOptions['where'])) {
            $strQuery .= ' WHERE ' . $arOptions['where'];
        }
        if (isset($arOptions['group'])) {
            $strQuery .= ' GROUP BY ' . $arOptions['group'];
        }
        if (isset($arOptions['order'])) {
            $strQuery .= ' ORDER BY ' . $arOptions['order'];
        }
        if (isset($arOptions['limit'])) {
            $strQuery .= ' LIMIT ' . $arOptions['limit'];
        }

        $arResult = array();

        $result = $connection->query($strQuery);

        if ($result === FALSE) {
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
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return array Empty array
     */
    public function saveTo($strConnection, array $options)
    {
        $connection = $this->getConnection($strConnection);

        $strQuery = 'UPDATE ';
        if (isset($tablePrefix['default'])) {
            $strQuery .= $tablePrefix['default']
                . '_' . $options['to'];
        } else {
            $strQuery .= $options['to'];
        }
        $strQuery .= ' SET ';
        foreach ($options['values'] as $strName => $value) {
            $arSets[] = $strName . '=' . $this->getSqlValue($options['columns'][$strName], $value, $connection);
        }
        $strQuery .= implode(',', $arSets);

        $strQuery .= ' WHERE ' . $options['where'];

        $arResult = array();
        $result = $connection->exec($strQuery);

        if ($result === FALSE) {
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
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return array Empty array
     */
    public function create($strConnection, array $options)
    {
        $connection = $this->getConnection($strConnection);

        $strQuery = 'INSERT INTO ';
        if (isset($tablePrefix['default'])) {
            $strQuery .= $tablePrefix['default']
                . '_' . $options['to'];
        } else {
            $strQuery .= $options['to'];
        }

        $strQuery .= ' (' . implode(',', array_keys($options['values'])) . ')';
        $strQuery .= ' VALUES (';
        $arValues = array();
        foreach ($options['values'] as $strName => $value) {
            $arValues[] = $this->getSqlValue($options['columns'][$strName], $value, $connection);
        }
        $strQuery .= implode(',', $arValues) . ')';

        $arResult = array();
        $result = $connection->exec($strQuery);

        if ($result === FALSE) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return $arResult;
    }

    /**
     * Truncates Data to a connection (DB, SOAP, File)
     *
     * @param string $strConnection Name of connection
     * @param array  $arOptions     Options to load the data
     *
     * @return array Empty array
     */
    public function truncate($strConnection, array $options)
    {
        $connection = $this->getConnection($strConnection);

        if (isset($tablePrefix['default'])) {
            $table .= $tablePrefix['default']
                . '_' . $options['table'];
        } else {
            $table .= $options['table'];
        }

        // Delete content of table
        $result = $connection->exec('DELETE FROM ' . $table);

        if ($result === FALSE) {
            $this->application->getResponse()->addError($connection->lastErrorMsg());
            throw new \ForwardFW\Exception\DataHandler(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }

        // Reset auto_inc counter
        $result = $connection->exec('DELETE FROM SQLITE_SEQUENCE WHERE name = \'' . $table . '\'');

        if ($result === FALSE) {
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
     * @param string $strConnection Name of connection
     *
     * @return void
     */
    public function initConnection($strConnection)
    {
        $arConfig = $this->getConfigParameter($strConnection);

        if (isset($arConfig['prefix'])) {
            $this->arTablePrefix[$strConnection] = $arConfig['prefix'];
        }
        try {
            $connection = new \SQLite3($arConfig['dsn'], SQLITE3_OPEN_READWRITE);
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
        $this->arConnectionCache[$strConnection] = $connection;
    }

    public function getSqlValue($strType, $value, $connection)
    {
        if ($strType === 'integer') {
            return (int) $value;
        }
        return '\'' . $connection->escapeString($value) . '\'';
    }
}
