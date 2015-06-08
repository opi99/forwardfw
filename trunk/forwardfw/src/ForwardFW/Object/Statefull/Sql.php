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
 * @subpackage Object
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.1
 */

namespace ForwardFW\Object\Statefull;

/**
 * A object/model that can load themself from DB.
 *
 * @category   Object
 * @package    ForwardFW
 * @subpackage Object
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Sql extends \ForwardFW\Object\Statefull
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
     * @param string $strIdFieldName  Fieldname which holds the object id.
     * @param string $strTablePrefix  Prefix der Tabellennamen im Projekt.
     * @param string $strDBConnection Name of the DB connection to use.
     *
     * @return new instance
     */
    public function __construct(
        $strIdFieldName = 'id',
        $strTablePrefix = '',
        $strDBConnection = 'default'
    ) {
        parent::__construct(null, $strIdFieldName);

        $this->strTablePrefix  = $strTablePrefix;
        $this->strDBConnection = $strDBConnection;

        $this->strTableName = self::resolveTableName(
            $this->strTablePrefix,
            get_class($this)
        );
    }

    public function setApplication(\ForwardFW\Controller\ApplicationInterface $application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Returns the name of table out of prefix and object name cobined with
     * an underscore and lowercased.
     *
     * @param string $strTablePrefix Prefix der Tabellennamen im Projekt.
     * @param string $strObjectName  Name of the object
     *
     * @return string The table name.
     */
    public static function resolveTableName($strTablePrefix, $strObjectName)
    {
        $result = ($strTablePrefix != '' ? $strTablePrefix . '_' : '');
        $result .= substr($strObjectName, strrpos($strObjectName, '\\') + 1);
        return strtolower($result);
    }

    /**
     * Loads a the object from DB by the given ID.
     *
     * @param mixed $id The ID for the object.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadById($id)
    {
        return $this->loadByWhereClause($this->strIdFieldName . '=' . $id);
    }

    /**
     * Loads the object by where clause. Only first hit will be selected.
     *
     * @param string $strWhereClause The SQL where clause to search for this object.
     *
     * @return boolean True if object was loadable otherwise false.
     */
    public function loadByWhereClause($strWhereClause)
    {
        $dataHandler = \ForwardFW\Controller\DataHandler::getInstance(
            $this->application
        );
        $arResult = $dataHandler->loadFrom(
            $this->strDBConnection,
            array(
                'select' => '*',
                'from' => $this->strTableName,
                'where' => $strWhereClause,
                'limit' => '1',
            )
        );
        if ($arResult !== false && count($arResult) == 1) {
            $this->loadByArray($arResult[0]);
            return true;
        }
        return false;
    }

    /**
     * Saves the object to DB. If ID is set, then the DB will be updated. Otherwise
     * an insert statement will create it. The new ID will saved in object.
     *
     * @return boolean True if object was saveable otherwise false.
     */
    public function save()
    {
        $arToSave = array();
        $this->saveToArray($arToSave);

        unset($arToSave[$this->strIdFieldName]);

        $dataHandler = \ForwardFW\Controller\DataHandler::getInstance(
            $this->application
        );

        $id = $this->getId();
        if (empty($id)) {
            $isOk = $dataHandler->create(
                $this->strDBConnection,
                array(
                    'columns' => $this->getTableConfig(),
                    'to'      => $this->strTableName,
                    'values'  => $arToSave,
                ),
                new \ForwardFW\Callback(array($this, 'setId'))
            );
        } else {
            $isOk = $dataHandler->saveTo(
                $this->strDBConnection,
                array(
                    'columns' => $this->getTableConfig(),
                    'to'      => $this->strTableName,
                    'values'  => $arToSave,
                    'where'   => $this->strIdFieldName . ' = ' . $this->getId(),
                )
            );
        }
        return $isOk;
    }

    public function getTableConfig()
    {
        return array(
            'id' => 'integer',
        );
    }
}
