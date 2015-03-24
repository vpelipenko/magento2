<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Translation\Model\Js;

interface DataProviderInterface
{
    /**
     * Get translation data
     * @param string $themePath
     * @return string[]
     */
    public function getData($themePath);
}
