<?php

namespace Cgit\YoastImport;

class Plugin
{
    /**
     * Constructor
     *
     * The admin page should only be created if Yoast has been installed and
     * activated.
     *
     * @return void
     */
    public function __construct()
    {
        if (!defined('WPSEO_FILE')) {
            return;
        }

        $admin = new Admin;
    }
}
