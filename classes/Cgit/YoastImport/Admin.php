<?php

namespace Cgit\YoastImport;

class Admin
{
    /**
     * Menu page title
     *
     * @var string
     */
    private $title = 'Yoast Import';

    /**
     * Menu page name
     *
     * @var string
     */
    private $name = 'cgit-wp-yoast-import';

    /**
     * Minimum user capability
     *
     * @var string
     */
    private $capability = 'activate_plugins';

    /**
     * Views directory
     *
     * @var string
     */
    private $views = '';

    /**
     * Constructor
     *
     * Assign the views directory based on the active plugin directory and
     * register the admin menu page.
     *
     * @return void
     */
    public function __construct()
    {
        $this->views = dirname(CGIT_YOAST_IMPORT_PLUGIN) . '/views';

        add_action('admin_menu', [$this, 'register']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    /**
     * Register menu page
     *
     * @return void
     */
    public function register()
    {
        add_menu_page($this->title, $this->title, $this->capability,
            $this->name, [$this, 'render']);
    }

    /**
     * Enqueue styles and scripts
     *
     * @return void
     */
    public function enqueue()
    {
        $name = pathinfo(CGIT_YOAST_IMPORT_PLUGIN, PATHINFO_FILENAME);
        $url = rtrim(plugin_dir_url(CGIT_YOAST_IMPORT_PLUGIN), '/');

        wp_enqueue_style($name, $url . '/css/style.css');
    }

    /**
     * Render menu page
     *
     * @return void
     */
    public function render()
    {
        $importer = new Importer;

        // Nothing to import? Nothing to see here.
        if (!$importer->data()) {
            include $this->views . '/header.php';
            include $this->views . '/none.php';
            include $this->views . '/footer.php';

            return;
        }

        // Import requested? Do it.
        if ($importer->requested()) {
            $importer->import();

            include $this->views . '/header.php';
            include $this->views . '/done.php';
            include $this->views . '/footer.php';

            return;
        }

        // Print the import summary and form
        include $this->views . '/header.php';
        include $this->views . '/data.php';
        include $this->views . '/form.php';
        include $this->views . '/footer.php';
    }
}
