<?php
declare(encoding = "utf-8");
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
 * @copyright  2009-2010 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    SVN: $Id: $
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.0.7
 */

/**
 *
 */
require_once 'ForwardFW/Controller/DataHandler.php';
require_once 'ForwardFW/Interface/Application.php';
require_once 'PEAR/MDB2.php';

/**
 * Managing DataLoading via PEAR::MDB2
 *
 * @category   Application
 * @package    ForwardFW
 * @subpackage Controller/DataHandler
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ForwardFW_Controller_DataHandler_MDB2 extends ForwardFW_Controller_DataHandler
{
    var $arTablePrefix = array();
    /**
     * Constructor
     *
     * @param ForwardFW_Interface_Application $_application The running application.
     *
     * @return void
     */
    public function __construct(ForwardFW_Interface_Application $_application)
    {
        parent::__construct($_application);
    }

    public function loadFromCached($strConnection, array $arOptions, $nCacheTimeout = -1)
    {
        // @TODO Not yet implemented
        return $this->loadFrom($strConnection, $arOptions);
    }

    public function loadFrom($strConnection, array $arOptions)
    {
        $conMDB2 = $this->getConnection($strConnection);

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
        $resultMDB2 = $conMDB2->query($strQuery);

        if (PEAR::isError($resultMDB2)) {
            $this->application->getResponse()->addError($resultMDB2->getMessage() . $resultMDB2->getUserinfo());
            return null;
            // @TODO throw exception
        }
        while ($arRow = $resultMDB2->fetchRow(MDB2_FETCHMODE_ASSOC)) {
            array_push($arResult, $arRow);
        }
        return $arResult;
    }

    public function saveTo($strConnection, array $options)
    {
        // @TODO
    }

    public function initConnection($strConnection)
    {
        $strTablePrefix = $this->getConfigParameter('options');// @TODO depends on connectionName

        $arConfig = $this->getConfigParameter($strConnection);

        if (isset($arConfig['prefix'])) {
            $this->arTablePrefix[$strConnection] = $arConfig['prefix'];
        }
        $options = array('portability' => MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE);
        $options = array_merge($options, $arConfig['options']);
        $conMDB2 = MDB2::connect($arConfig['dsn'], $options);

        if (PEAR::isError($conMDB2)) {
            $this->application->getResponse()->addError($conMDB2->getMessage() . $conMDB2->getUserinfo());
            var_dump($conMDB2->getMessage() . $conMDB2->getUserinfo());
            return null;
            // @TODO throw exception
        }

        $ret = $conMDB2->exec('set character set utf8');
        $this->arConnectionCache[$strConnection] = $conMDB2;
    }

}

?>