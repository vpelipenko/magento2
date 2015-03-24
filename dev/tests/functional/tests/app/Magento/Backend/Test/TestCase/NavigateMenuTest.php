<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Backend\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Backend\Test\Page\Adminhtml\Dashboard;

/**
 * Steps:
 * 1. Log in to backend.
 * 2. Navigate throught menu to the page.
 * 6. Perform asserts.
 *
 * @ZephyrId MAGETWO-34874
 */
class NavigateMenuTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS, MX, PS';
    /* end tags */

    /**
     * Run menu navigation test.
     *
     * @param Dashboard $dashboard
     * @param string $menuItem
     * @return void
     */
    public function test(Dashboard $dashboard, $menuItem)
    {
        $dashboard->open();
        $dashboard->getMenuBlock()->navigate($menuItem);
    }
}
