<?php
/**
 * Provider which handles registration of posttype.
 */

namespace OWC\PDC\Leges\PostType;

use OWC\PDC\Base\Foundation\ServiceProvider;
use OWC\PDC\Leges\Shortcode\ShortcodeServiceProvider;
use OWC\PDC\Leges\PostType\Commands\UpdatePrices;

/**
 * Provider which handles registration of posttype.
 */
class LegesPostTypeServiceProvider extends ServiceProvider
{

    /**
     * Prefix of the posttype.
     *
     * @var string $prefix
     */
    protected $prefix = '_pdc-lege';

    /**
     * Name of posttype.
     *
     * @var string $postType
     */
    protected $postType = 'pdc-leges';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->plugin->loader->addAction('init', $this, 'registerPostType');

        $this->plugin->loader->addAction('pre_get_posts', $this, 'filterAdminSearch');

        if (class_exists('\WP_CLI')) {
            \WP_CLI::add_command('owc-update-leges', [UpdatePrices::class, 'execute'], ['shortdesc' => 'Update lege prices when specified date has been reached.']);
        }
    }

    /**
     * Register the Leges posttype.
     *
     * @return void
     */
    public function registerPostType()
    {
        if (! function_exists('register_extended_post_type')) {
            require_once($this->plugin->getRootPath() . '/src/Leges/vendor/johnbillion/extended-cpts/extended-cpts.php');
        }

        $labels = [
            'name'               => _x('Leges', 'post type general name', 'pdc-leges'),
            'singular_name'      => _x('Lege', 'post type singular name', 'pdc-leges'),
            'menu_name'          => _x('Leges', 'admin menu', 'pdc-leges'),
            'name_admin_bar'     => _x('Leges', 'add new on admin bar', 'pdc-leges'),
            'add_new'            => _x('Add new lege', 'lege', 'pdc-leges'),
            'add_new_item'       => __('Add new lege', 'pdc-leges'),
            'new_item'           => __('New lege', 'pdc-leges'),
            'edit_item'          => __('Edit lege', 'pdc-leges'),
            'view_item'          => __('View lege', 'pdc-leges'),
            'all_items'          => __('All leges', 'pdc-leges'),
            'search_items'       => __('Search leges', 'pdc-leges'),
            'parent_item_colon'  => __('Parent leges:', 'pdc-leges'),
            'not_found'          => __('No leges found.', 'pdc-leges'),
            'not_found_in_trash' => __('No leges found in Trash.', 'pdc-leges')
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __('PDC leges', 'pdc-leges'),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => ['title'],
            'show_in_feed'       => false,
            'archive'            => false,
            'admin_cols'         => [
                'price'        => [
                    'title'    => __('Lege price (in &euro;)', 'pdc-leges'),
                    'meta_key' => "{$this->prefix}-price",
                ],
                'new-price'    => [
                    'title'    => __('Lege new price (in &euro;)', 'pdc-leges'),
                    'meta_key' => "{$this->prefix}-new-price",
                ],
                'active-date'  => [
                    'title'       => __('Date new lege active', 'pdc-leges'),
                    'meta_key'    => "{$this->prefix}-active-date",
                    'date_format' => 'd/m/Y'
                ],
                'code-preview' => [
                    'title'    => __('Lege shortcode', 'pdc-leges'),
                    'function' => function () {
                        echo ShortcodeServiceProvider::generateShortcode(get_the_ID());
                    }
                ],
                'published'    => [
                    'title'       => __('Published', 'pdc-leges'),
                    'post_field'  => 'post_date',
                    'date_format' => 'd M Y'
                ],
                'modified'    => [
                    'title'       => __('Modified', 'pdc-leges'),
                    'post_field'  => 'post_modified',
                    'date_format' => 'd M Y'
                ],
            ],
        ];

        return register_extended_post_type($this->postType, $args, $labels);
    }

	/**
	 * If the search term equals a post ID, return only that post.
	 */
	public function filterAdminSearch(WP_Query $query)
	{
		if (!is_admin()) {
			return;
		}

		if (($query->query['post_type'] ?? null) !== $this->postType) {
			return;
		}

		$searchTerm = $query->query_vars['s'] ?? null;

		if (!$searchTerm || !is_numeric($searchTerm)) {
			return;
		}

		$searchTerm = (int)$searchTerm;

		$postIds = get_posts([
			'fields' => 'ids',
			'post_type' => $this->postType,
			'posts_per_page' => -1,
		]);

		if (!in_array($searchTerm, $postIds, true)) {
			return;
		}

		$query->set('s', '');
		$query->set('p', $searchTerm);
	}
}
