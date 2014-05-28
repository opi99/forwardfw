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
 * @category   Object
 * @package    ForwardFW
 * @subpackage Container
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2014 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW\Container;

/**
 * A list of models that can load themself from DB.
 *
 * @category   Object
 * @package    ForwardFW
 * @subpackage Container
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Sql extends \ForwardFW\Container
{
    /**
     * Name of the table, which is mostly a prefix and the object name
     * in lowercase.
     *
     * @var string
     */
    protected $strTableName = '';

    /**
     * Prefix for the table name
     *
     * @access private
     * @var string
     */
    protected $strTablePrefix = '';

    /**
     * Name of the connection which should be used for this object to load/save.
     *
     * @access private
     * @var string
     */
    protected $strDBConnection = '';

    /**
     * The application this container is running in.
     *
     * @access private
     * @var \ForwardFW\Controller\ApplicationInterface
     */
    protected $application = null;

    /**
     * constructor
     *
     * @param string $strTablePrefix  Prefix der Tabellennamen im Projekt.
     * @param string $strDBConnection Name of the DB connection to use.
     *
     * @return new instance
     */
    public function __construct($strTablePrefix = '', $strDBConnection = 'default')
    {
        parent::__construct();

        $this->strTablePrefix  = $strTablePrefix;
        $this->strDBConnection = $strDBConnection;

        $this->strTableName = \ForwardFW\Object\Statefull\Sql::resolveTableName(
            $this->strTablePrefix,
            $this->strObjectName
        );
    }

    public function setApplication(\ForwardFW\Controller\ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * Loads all data from given table.
     *
     * @param boolean $bHidden If true then also return the hidden objects
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadAll($bHidden = false)
    {
        return $this->loadByWhereClause(
            $this->buildWhereClause($bHidden)
        );
    }

    /**
     * Returns count of all objects in DB
     *
     * @param string  $strWhereClause Where clause for the select.
     * @param boolean $bHidden        If true then also count the hidden objects
     *
     * @return integer
     */
    public function countAll($bHidden = false)
    {
        return $this->countByWhereClause(
            $this->buildWhereClause($bHidden)
        );
    }

    /**
     * Returns where clouse depending with deleted and hidden fields.
     *
     * @param boolean $bHidden If true then also count the hidden objects
     *
     * @return string
     */
    public function buildWhereClause($bHidden, array $arFields = null)
    {
        if (null !== $arFields) {
            foreach ($arFields as $strField => $value) {
                if ($value !== null) {
                    $arWhere[] = $strField . '="' . $value . '"';
                }
            }
        }

        if (!$bHidden) {
            $arWhere[] = 'hidden=0';
        }
        $arWhere[] = 'deleted=0';

        return implode(' AND ', $arWhere);
    }

    /**
     * Returns count of objects in DB
     *
     * @param string $strWhereClause Where clause for the select.
     *
     * @return integer
     */
    public function countByWhereClause($strWhereClause)
    {
        $dataHandler = \ForwardFW\Controller\DataHandler::getInstance(
            $this->application
        );
        $arResult = $dataHandler->loadFrom(
            $this->strDBConnection,
            array(
                'select' => 'count(id)',
                'from'   => $this->strTableName,
                'where'  => $strWhereClause,
            )
        );

        $row = reset($arResult);
        return (int) reset($row);
    }

    /**
     * Loads all data from given table by given where clause.
     *
     * @param string $strWhereClause Where clause for the select.
     * @param string $strGroupBy     Group by clause for the select.
     * @param string $strOrderBy     Order by clause for the select.
     * @param string $strLimit       Limit clause for the select.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadByWhereClause(
        $strWhereClause = null,
        $strGroupBy = null,
        $strOrderBy = null,
        $strLimit = null
    ) {
        $dataHandler = \ForwardFW\Controller\DataHandler::getInstance(
            $this->application
        );
        $arResult = $dataHandler->loadFrom(
            $this->strDBConnection,
            array(
                'select' => '*',
                'from'   => $this->strTableName,
                'where'  => $strWhereClause,
                'group'  => $strGroupBy,
                'order'  => (is_null($strOrderBy) ? $this->strOrderBy : $strOrderBy),
                'limit'  => $strLimit,
            )
        );
        if ($arResult !== false) {
            $this->loadByArray($arResult);
            return true;
        }
        return false;
    }
}
