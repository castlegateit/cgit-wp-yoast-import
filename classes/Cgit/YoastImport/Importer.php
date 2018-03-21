<?php

namespace Cgit\YoastImport;

class Importer
{
    /**
     * Data to import
     *
     * @var array
     */
    private $data = [];

    /**
     * POST key to trigger import
     *
     * @var string
     */
    private $key = 'import';

    /**
     * Constructor
     *
     * When the class is instantiated, populate the list of SEO titles and
     * descriptions to be imported.
     *
     * @return void
     */
    public function __construct()
    {
        $this->assembleData();
    }

    /**
     * Assemble data to import
     *
     * Assemble a single set of results that includes the post ID, title, SEO
     * title, and SEO description of each post that has either an SEO title or
     * an SEO description.
     *
     * @return void
     */
    private function assembleData()
    {
        global $wpdb;

        $posts = $wpdb->posts;
        $postmeta = $wpdb->postmeta;

        $this->data = $wpdb->get_results("SELECT posts.ID AS post_id,
                posts.post_title,
                posts.post_type,
                titles.meta_value AS seo_title,
                descriptions.meta_value AS seo_description
            FROM $posts AS posts
            JOIN $postmeta AS titles
                ON posts.ID = titles.post_id
            JOIN $postmeta AS descriptions
                ON posts.ID = descriptions.post_id
            WHERE posts.post_type != 'revision'
                AND titles.meta_key = 'seo_title'
                AND descriptions.meta_key = 'seo_description'
                AND (titles.meta_value != ''
                    OR descriptions.meta_value != '')
            ORDER BY post_id ASC;");
    }

    /**
     * Return SEO data found in database
     *
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Has the import been requested?
     *
     * @return boolean
     */
    public function requested()
    {
        return isset($_POST[$this->key]) && $_POST[$this->key];
    }

    /**
     * Import SEO data
     *
     * Save the SEO titles and descriptions to their corresponding Yoast fields
     * in the database without overwriting existing values.
     *
     * @return void
     */
    public function import()
    {
        $title_key = '_yoast_wpseo_title';
        $desc_key = '_yoast_wpseo_metadesc';

        foreach ($this->data as $item) {
            $title = $item->seo_title;
            $desc = $item->seo_description;

            if ($title && !get_post_meta($id, $title_key, true)) {
                update_post_meta($id, $title_key, $title);
            }

            if ($desc && !get_post_meta($id, $desc_key, true)) {
                update_post_meta($id, $desc_key, $desc);
            }
        }
    }
}
