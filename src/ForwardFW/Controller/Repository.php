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

namespace ForwardFW\Controller;

/**
 * Managing DataLoading via PEAR::MDB
 */
abstract class Repoitory extends \ForwardFW\Controller implements RepositoryInterface
{
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
}
