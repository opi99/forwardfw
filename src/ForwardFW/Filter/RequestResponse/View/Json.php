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
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @copyright  2015 The Authors
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 * @since      File available since Release 0.1.0
 */

namespace ForwardFW\Filter\RequestResponse\View;

/**
 * This class loads and runs the requested Application.
 *
 * @category   Filter
 * @package    ForwardFW
 * @subpackage RequestResponse
 * @author     Alexander Opitz <opitz.alexander@primacom.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link       http://forwardfw.sourceforge.net
 */
class Json extends \ForwardFW\Filter\RequestResponse
{
    /**
     * Function to process before your child
     *
     * @return void
     */
    public function doIncomingFilter()
    {
        $this->response->setContentType('application/json');

        $errorEntries = $this->response->getErrors()->getEntries();
        if ($errorEntries) {
            $response = $this->getJsonErrorResponse($errorEntries);
        } else {
            $response = $this->getJsonResponse();
        }

        $this->response->addContent(
            json_encode($response)
        );
    }

    /**
     * Builds the JSON response array from response data.
     *
     * @return string[] The standard JSON response as array.
     */
    private function getJsonResponse()
    {
        $data = $this->response->getData('json');
        try {
            if (is_object($data) &&  method_exists($data, 'getForJson')) {
                $data = $data->getForJson();
            }
            $response = array(
                'success' => true,
                'data' => $data,
            );
        } catch (\Exception $e) {
            $response = $this->getJsonErrorResponse(array($e->getMessage()));
            $this->response->addError($e->getMessage())
                ->setHttpStatus(400);
        }
    }

    /**
     * Builds the JSON response array from given errors.
     *
     * @param string[] Array of Error strings to send as message.
     * @return string[] The standard JSON response as array.
     */
    private function getJsonErrorResponse($errorEntries)
    {
        return array(
            'success' => false,
            'message' => $errorEntries,
        );
    }

    /**
     * Function to process after your child
     *
     * @return void
     */
    public function doOutgoingFilter()
    {
    }
}
