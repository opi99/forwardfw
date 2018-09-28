<?php
/**
 * This file is part of ForwardFW a web application framework.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2009-2018 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.2.0
 */

namespace ForwardFW\Filter\RequestResponse;

/**
 * This class sends the log and error message queue to the client via ChromeLogger.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class ChromeLogger extends \ForwardFW\Filter\RequestResponse
{
    protected $chromeLogger = null;
    
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->addLog('Enter Filter');
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
        $this->response->addLog('Leave Filter');
        $this->chromeLogger = new \Kodus\Logging\ChromeLogger();

        $this->outputLog();
        $this->outputError();

        $this->chromeLogger->emitHeader();
    }

    /**
     * Adds the response log entries to the log group in FirePHP
     *
     * @return void
     */
    private function outputLog()
    {
        $arLogs = $this->response->getLogs()->getEntries();
        foreach ($arLogs as $strMessage) {
            $this->chromeLogger->notice($strMessage);
        }
    }


    /**
     * Adds the response error entries to the error group in FirePHP
     *
     * @return void
     */
    public function outputError()
    {
        $arErrors = $this->response->getErrors()->getEntries();
        foreach ($arErrors as $strMessage) {
            $this->chromeLogger->error($strMessage);
        }
    }
}
