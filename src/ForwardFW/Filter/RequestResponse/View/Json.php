<?php
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

namespace ForwardFW\Filter\RequestResponse\View;

/**
 * This class loads and runs the requested Application.
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
    protected function getJsonResponse()
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

        return $response;
    }

    /**
     * Builds the JSON response array from given errors.
     *
     * @param string[] Array of Error strings to send as message.
     * @return string[] The standard JSON response as array.
     */
    protected function getJsonErrorResponse($errorEntries)
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
