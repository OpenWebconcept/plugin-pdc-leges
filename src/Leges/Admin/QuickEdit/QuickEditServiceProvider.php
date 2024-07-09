<?php

namespace OWC\PDC\Leges\Admin\QuickEdit;

use OWC\PDC\Base\Foundation\ServiceProvider;
use WP_Post;

class QuickEditServiceProvider extends ServiceProvider
{
    /**
     * Prefix for the quickedit.
     */
    protected string $prefix = '_pdc-lege';

    /**
     * Name of posttype for the quickedit.
     */
    protected string $postType = 'pdc-leges';

    /**
     * Array holding all the quickedit handlers.
     */
    protected array $quickEditHandlers = [];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->setQuickEditHandlers();

        $this->plugin->loader->addAction('quick_edit_custom_box', $this, 'registerQuickEditHandler', 10, 2);
        $this->plugin->loader->addAction('save_post', $this, 'registerSavePostHandler', 10, 2);
        $this->plugin->loader->addAction('admin_footer', $this, 'renderFooterScript', 10, 1);
        $this->plugin->loader->addFilter('post_row_actions', $this, 'addRowActions', 10, 2);
    }

    /**
     * Adds the handlers.
     */
    public function addRowActions(array $actions, WP_Post $post): array
    {
        foreach ($this->getQuickEditHandlers() as $key => $handler) {
            $foundValue = get_post_meta($post->ID, $handler['metaboxKey'], true);

            if (! $foundValue) {
                continue;
            }

            if (isset($actions['inline hide-if-no-js'])) {
                $newAttribute = sprintf('data-%s="%s"', esc_attr($key), esc_attr($foundValue));
                $actions['inline hide-if-no-js'] = str_replace('class=', "$newAttribute class=", $actions['inline hide-if-no-js']);
            }
        }

        return $actions;
    }

    /**
     * Render javascript inline for admin only.
     */
    public function renderFooterScript(): void
    {
        $current_screen = get_current_screen();
        if ('edit-' . $this->postType !== $current_screen->id || $current_screen->post_type !== $this->postType) {
            return;
        }

        // Ensure jQuery library loads
        wp_enqueue_script('jquery');
        wp_enqueue_style('jquery-ui-datepicker', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css');
        wp_enqueue_script('jquery-ui-datepicker'); ?>
<script type="text/javascript">
    jQuery(function($) {
        $(function() {
            $('input[name="post_password"]').each(function(i) {
                $(this).parent().parent().parent().remove();
            });
            $('input[name="post_name"]').each(function(i) {
                $(this).parent().parent().remove();
            });
            $('#_pdc-lege-active-date').datepicker({
                altField: "#_pdc-lege-active-date",
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                minDate: 0
            });
        });

        $('#the-list').on('click', '.editinline', function(e) {
            e.preventDefault();
            inlineEditPost.revert();
            <?php foreach ($this->quickEditHandlers as $key => $handler) { ?>
            var value = $(this).data('<?php echo $key; ?>');
            $('#<?php echo $handler['metaboxKey']; ?>').val(value ? value : '');
            <?php } ?>
        });
    });

</script>
        <?php
    }

    /**
     * Adds the quickedit handlers.
     */
    public function registerQuickEditHandler(string $columnName, string $postType): void
    {
        if (! in_array($columnName, array_keys($this->quickEditHandlers))) {
            return;
        }

        echo '<fieldset class="inline-edit-col-left clear">
				<div class="inline-edit-col">';

        foreach ($this->quickEditHandlers as $key => $handler) {
            if ($columnName !== $key) {
                continue;
            }

            $method = explode('-', $key);
            $method = array_map('ucfirst', $method);
            $method = implode('', $method);

            if (method_exists($this, $method)) {
                $this->$method($handler);
            }
        }

        echo '</div>
			</fieldset>';
    }

    /**
     * Adds active date to quickedit handler.
     */
    protected function activeDate(array $item): void
    {
        $value = get_post_meta(get_the_ID(), $item['metaboxKey'], true); ?>

		<label class="aligncenter" for="<?php echo $item['metaboxKey']; ?>">
			<span class="title"><?php echo __($item['label'], 'pdc-leges'); ?></span>
			<span class="input-text-wrap"><input type="text" id="<?php echo $item['metaboxKey']; ?>" name="<?php echo $item['metaboxKey']; ?>" value="<?php echo $value; ?>"></span>
		</label>
        <?php
    }

    /**
     * Adds price to quickedit handlers.
     */
    protected function price(array $item): void
    {
        $value = get_post_meta(get_the_ID(), $item['metaboxKey'], true); ?>

		<label class="aligncenter" for="<?php echo $item['metaboxKey']; ?>">
			<span class="title"><?php echo __($item['label'], 'pdc-leges'); ?></span>
			<span class="input-text-wrap"><input type="text" id="<?php echo $item['metaboxKey']; ?>" name="<?php echo $item['metaboxKey']; ?>" value="<?php echo $value; ?>"></span>
		</label>
        <?php
    }

    /**
     * Adds newPrice to quickedit handlers.
     */
    protected function newPrice(array $item): void
    {
        $value = get_post_meta(get_the_ID(), $item['metaboxKey'], true); ?>

		<label class="aligncenter" for="<?php echo $item['metaboxKey']; ?>">
			<span class="title"><?php echo __($item['label'], 'pdc-leges'); ?></span>
			<span class="input-text-wrap"><input type="text" id="<?php echo $item['metaboxKey']; ?>" name="<?php echo $item['metaboxKey']; ?>" value="<?php echo $value; ?>"></span>
		</label>
        <?php
    }

    /**
     * Returns the handlers for the quick edit.
     */
    public function setQuickEditHandlers(): array
    {
        return $this->quickEditHandlers = [
            'new-price' => [
                'metaboxKey' => sprintf('%s-%s', $this->prefix, 'new-price'),
                'label' => __('New price', 'pdc-leges'),
            ],
            'price' => [
                'metaboxKey' => sprintf('%s-%s', $this->prefix, 'price'),
                'label' => __('Price', 'pdc-leges'),
            ],
            'active-date' => [
                'metaboxKey' => sprintf('%s-%s', $this->prefix, 'active-date'),
                'label' => __('Date new lege active', 'pdc-leges'),
            ],
        ];
    }

    /**
     * Handles the save post handler.
     */
    public function registerSavePostHandler(int $postID, WP_Post $post): void
    {
        if (wp_is_post_revision($postID)) {
            return;
        }

        if (wp_is_post_autosave($postID)) {
            return;
        }

        // if this correct post type?
        if ($post->post_type !== $this->postType) {
            return;
        }

        // does this user have permissions?
        if (! current_user_can('edit_post', $postID)) {
            return;
        }

        foreach ($this->getQuickEditHandlers() as $key => $handler) {
            // update!
            if (isset($_POST["{$this->prefix}-{$key}"])) {
                update_post_meta($postID, "{$this->prefix}-{$key}", $_POST["{$this->prefix}-{$key}"]);
            }
        }
    }

    /**
     * Gets the registered quickedit handlers.
     */
    private function getQuickEditHandlers(): array
    {
        return $this->quickEditHandlers;
    }
}
