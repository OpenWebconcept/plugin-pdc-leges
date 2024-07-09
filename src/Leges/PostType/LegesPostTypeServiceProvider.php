<?php

namespace OWC\PDC\Leges\PostType;

use Extended_CPT;
use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\PostType\Commands\UpdatePrices;
use OWC\PDC\Leges\Shortcode\ShortcodeServiceProvider;
use WP_CLI;

class LegesPostTypeServiceProvider extends ServiceProvider
{
    protected string $prefix = '_pdc-lege';
    protected string $postType = 'pdc-leges';

    public function register(): void
    {
        $this->plugin->loader->addAction('init', $this, 'registerPostType');
        $this->plugin->loader->addFilter("ext-cpts/{$this->postType}/filter-query/{$this->prefix}_post_id", $this, 'filterByPostId', 10, 3);

        if (class_exists('\WP_CLI')) {
            WP_CLI::add_command('owc-update-leges', [UpdatePrices::class, 'execute'], ['shortdesc' => 'Update lege prices when specified date has been reached.']);
        }
    }

    public function registerPostType(): Extended_CPT
    {
        if (! function_exists('register_extended_post_type')) {
            require_once($this->plugin->getRootPath() . '/src/Leges/vendor/johnbillion/extended-cpts/extended-cpts.php');
        }

        $labels = [
            'name' => _x('Leges', 'post type general name', 'pdc-leges'),
            'singular_name' => _x('Lege', 'post type singular name', 'pdc-leges'),
            'menu_name' => _x('Leges', 'admin menu', 'pdc-leges'),
            'name_admin_bar' => _x('Leges', 'add new on admin bar', 'pdc-leges'),
            'add_new' => _x('Add new lege', 'lege', 'pdc-leges'),
            'add_new_item' => __('Add new lege', 'pdc-leges'),
            'new_item' => __('New lege', 'pdc-leges'),
            'edit_item' => __('Edit lege', 'pdc-leges'),
            'view_item' => __('View lege', 'pdc-leges'),
            'all_items' => __('All leges', 'pdc-leges'),
            'search_items' => __('Search leges', 'pdc-leges'),
            'parent_item_colon' => __('Parent leges:', 'pdc-leges'),
            'not_found' => __('No leges found.', 'pdc-leges'),
            'not_found_in_trash' => __('No leges found in Trash.', 'pdc-leges'),
        ];

        $args = [
            'labels' => $labels,
            'description' => __('PDC leges', 'pdc-leges'),
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 5,
            'supports' => ['title'],
            'show_in_feed' => false,
            'archive' => false,
            'admin_cols' => [
                'price' => [
                    'title' => __('Lege price (in &euro;)', 'pdc-leges'),
                    'meta_key' => "{$this->prefix}-price",
                ],
                'new-price' => [
                    'title' => __('Lege new price (in &euro;)', 'pdc-leges'),
                    'meta_key' => "{$this->prefix}-new-price",
                ],
                'active-date' => [
                    'title' => __('Date new lege active', 'pdc-leges'),
                    'meta_key' => "{$this->prefix}-active-date",
                    'date_format' => 'd/m/Y',
                ],
                'code-preview' => [
                    'title' => __('Lege shortcode', 'pdc-leges'),
                    'function' => function () {
                        echo ShortcodeServiceProvider::generateShortcode(get_the_ID() ?: 0);
                    },
                ],
                'published' => [
                    'title' => __('Published', 'pdc-leges'),
                    'post_field' => 'post_date',
                    'date_format' => 'd M Y',
                ],
                'modified' => [
                    'title' => __('Modified', 'pdc-leges'),
                    'post_field' => 'post_modified',
                    'date_format' => 'd M Y',
                ],
            ],
            'admin_filters' => [
                "{$this->prefix}_post_id" => [
                    'title' => 'ID',
                    'meta_search_key' => '', // Use the "meta_search_key" type in order to generate a text input
                ],
            ],
        ];

        return register_extended_post_type($this->postType, $args, $labels);
    }

    public function filterByPostId(array $return, array $query, $filter): array
    {
        $postId = trim($query["{$this->prefix}_post_id"] ?? '');

        if ($postId) {
            $return['p'] = is_numeric($postId) ? (int)$postId : -1; // Non-numeric input should return no posts
        }

        return $return;
    }
}
