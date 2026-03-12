<?php

declare(strict_types=1);

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

namespace ForwardFW\Service\DataHandler;

use \ForwardFW\Exception\DataHandlerException;

/**
 * Managing DataLoading via PHPs PDO
 */
class Pdo extends \ForwardFW\Service\DataHandler
{
    /**
     * Loads Data from a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return array Data from the connection.
     */
    public function loadFrom(string $connectionName, array $options): array
    {
        $connection = $this->getConnection($connectionName);

        $params = [];

        $query = 'SELECT ' . $options['select'] . ' FROM ' . $this->getTableName($options['from'], $connectionName);
        if (isset($options['where'])) {
            $query .= ' WHERE ' . $this->buildWhere($options['where'], $params);
        }

        if (!empty($options['order'])) {
            $query .= ' ORDER BY ' . $this->buildOrder($options['order']);
        }

        if (isset($options['limit'])) {
            $query .= ' LIMIT ' . $this->buildLimit($options['limit']);
        }

        try {
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new DataHandlerException($e->getMessage() . ' Used query: ' . $query);
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return int rows updated
     */
    public function update(string $connectionName, array $options): int
    {
        $connection = $this->getConnection($connectionName);

        $table = $this->getTableName($options['to'], $connectionName);

        $params = [];

        $query =
            'UPDATE ' . $table .
            ' SET ' . $this->buildUpdate($options['values'], $params);

        if (!empty($options['where'])) {
            $query .= ' WHERE ' . $this->buildWhere($options['where'], $params);
        }

        try {
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new DataHandlerException($e->getMessage() . ' Used query: ' . $query);
        }

        return $stmt->rowCount();
    }

    /**
     * Saves Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     *
     * @return ?int Last insert id if requested
     */
    public function create(string $connectionName, array $options): ?int
    {
        $connection = $this->getConnection($connectionName);

        $table = $this->getTableName($options['to'], $connectionName);

        $params = [];

        $insert = $this->buildInsert($options['values'], $params);

        $query =
            'INSERT INTO ' . $table .
            ' (' . $insert['columns'] . ')' .
            ' VALUES (' . $insert['values'] . ')';

        try {
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new DataHandlerException($e->getMessage() . ' Used query: ' . $query);
        }

        if ($options['returnId'] ?? false) {
            return (int)$connection->lastInsertId();
        }

        return null;
    }

    public function delete($connectionName, array $options): int
    {
        $connection = $this->getConnection($connectionName);

        $table = $this->getTableName($options['from'], $connectionName);

        $params = [];

        $query = 'DELETE FROM ' . $table;

        if (!empty($options['where'])) {
            $query .= ' WHERE ' . $this->buildWhere($options['where'], $params);
        }

        try {
            $stmt = $connection->prepare($query);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            throw new DataHandlerException($e->getMessage() . ' Used query: ' . $query);
        }

        return $stmt->rowCount();
    }

    /**
     * Truncates Data to a connection (DB, SOAP, File)
     *
     * @param string $connectionName Name of connection
     * @param array $options Options to load the data
     */
    public function truncate(string $connectionName, array $options): void
    {
        $connection = $this->getConnection($connectionName);

        $table = $this->getTableName($options['table'], $connectionName);

        // Delete content of table
        $result = $connection->exec('DELETE FROM ' . $table);

        if ($result === false) {
            throw new DataHandlerException(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }

        // Reset auto_inc counter
        $result = $connection->exec('DELETE FROM SQLITE_SEQUENCE WHERE name = \'' . $table . '\'');

        if ($result === false) {
            throw new DataHandlerException(
                'Error while execute: ' . $connection->lastErrorMsg()
            );
        }
        return;
    }

    /**
     * Loads and initialize the connection handler.
     *
     * @param string $connectionName Name of connection
     *
     * @return void
     */
    public function initConnection($connectionName): void
    {
        try {
            $connection = new \PDO($this->config->getDsn(), $this->config->getUsername(), $this->config->getPassword());
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new DataHandlerException(
                'Cannot initialize PDO Connection: '
                . $e->getMessage()
            );
        }

        $this->connectionCache[$connectionName] = $connection;
    }

    private function buildWhere(array $where, array &$params, string $logic = 'AND'): string
    {
        $parts = [];

        foreach ($where as $key => $value) {

            // Support nested OR / AND groups
            if ($key === 'OR' || $key === 'AND') {
                $nested = $this->buildWhere($value, $params, $key);

                if ($nested !== '') {
                    $parts[] = '(' . $nested . ')';
                }

                continue;
            }

            // Extract operator from key
            if (preg_match('/^(.+?)\s*(>=|<=|!=|=|>|<|LIKE|IN)$/i', $key, $match)) {
                $field = trim($match[1]);
                $operator = strtoupper($match[2]);
            } else {
                throw new DataHandlerException('Operator is missing');
            }

            if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $field)) {
                throw new DataHandlerException('Invalid field name: ' . $field);
            }

            // NULL handling
            if ($value === null) {

                if ($operator === '!=') {
                    $parts[] = $field . ' IS NOT NULL';
                } else {
                    $parts[] = $field . ' IS NULL';
                }

                continue;
            }

            // IN operator
            if ($operator === 'IN') {

                if (!is_array($value) || empty($value)) {
                    throw new DataHandlerException('IN operator requires a non-empty array');
                }

                $inParams = [];

                foreach ($value as $v) {
                    $param = 'p' . count($params);
                    $params[$param] = $v;
                    $inParams[] = ':' . $param;
                }

                $parts[] = $field . ' IN (' . implode(',', $inParams) . ')';

                continue;
            }

            // Normal comparison
            $param = 'p' . count($params);
            $params[$param] = $value;

            $parts[] = $field . ' ' . $operator . ' :' . $param;
        }

        return implode(' ' . $logic . ' ', $parts);
    }

    private function buildOrder(array $order): string
    {
        $parts = [];

        foreach ($order as $field => $direction) {

            if (is_int($field)) {
                $field = $direction;
                $direction = 'ASC';
            }

            $direction = strtoupper($direction);

            if (!in_array($direction, ['ASC', 'DESC'], true)) {
                throw new DataHandlerException('Invalid ORDER direction');
            }

            if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $field)) {
                throw new DataHandlerException('Invalid ORDER field');
            }

            $parts[] = $field . ' ' . $direction;
        }

        return implode(', ', $parts);
    }

    private function buildLimit($limit): string
    {
        if (is_array($limit)) {

            $offset = (int)($limit['offset'] ?? 0);
            $count  = (int)($limit['count'] ?? 0);

            return $offset . ',' . $count;
        }

        return (string)(int)$limit;
    }

    private function buildInsert(array $values, array &$params): array
    {
        $columns = [];
        $placeholders = [];

        foreach ($values as $column => $value) {

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                throw new DataHandlerException('Invalid column name: ' . $column);
            }

            $param = 'p' . count($params);

            $columns[] = $column;
            $placeholders[] = ':' . $param;

            $params[$param] = $value;
        }

        return [
            'columns' => implode(',', $columns),
            'values' => implode(',', $placeholders)
        ];
    }

    private function buildUpdate(array $values, array &$params): string
    {
        $parts = [];

        foreach ($values as $column => $value) {

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                throw new DataHandlerException('Invalid column name: ' . $column);
            }

            $param = 'p' . count($params);

            $parts[] = $column . ' = :' . $param;
            $params[$param] = $value;
        }

        return implode(', ', $parts);
    }

    /**
     * Returns real table name Prefix or DB dependent changes.
     *
     * @param string $tableName Name of table inside application
     * @param string $connectionName Name of connection
     *
     * @return string Name of table inside DB
     */
    protected function getTableName(string $tableName, string $connectionName): string
    {
        $tablePrefix = $this->config->getTablePrefix();

        if ($tablePrefix !== '') {
            return $tablePrefix . '_' . $tableName;
        } else {
            return $tableName;
        }
    }
}
