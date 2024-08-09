<?php

namespace OWC\PDC\Leges\Tests\Admin\QuickEdit;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Leges\Admin\QuickEdit\QuickEditServiceProvider;
use OWC\PDC\Leges\Tests\Unit\TestCase;
use WP_Mock;
use WP_Post;

class QuickEditServiceProviderTest extends TestCase
{
    /**
     * @var
     */
    protected $service;

    /**
     * @var
     */
    protected $plugin;

    /**
     * @var array
     */
    protected $stub;

    /**
     * @var
     */
    protected $post;

    public function setUp(): void
    {
        Parent::setUp();

        $this->setRunTestInSeparateProcess(true);
        $this->setRunClassInSeparateProcess(true);

        $config = m::mock(Config::class);
        $this->plugin = m::mock(Plugin::class);

        $this->plugin->config = $config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->stub = [
            'new-price' => [
                'metaboxKey' => 'new-price',
                'label' => 'New price',
            ],
            'price' => [
                'metaboxKey' => 'price',
                'label' => 'Price',
            ],
            'active-date' => [
                'metaboxKey' => 'active-date',
                'label' => 'Date new lege active',
            ],
        ];

        $this->post = m::mock(WP_Post::class);
        $this->post->ID = 5;
        $this->post->post_type = 'page';

        $this->service = new QuickEditServiceProvider($this->plugin);
    }

    public function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_registers_hook_correctly()
    {
        $this->plugin->loader->shouldReceive('addAction')->withArgs([
            'quick_edit_custom_box',
            $this->service,
            'registerQuickEditHandler',
            10,
            2,
        ])->once();

        $this->plugin->loader->shouldReceive('addAction')->withArgs([
            'save_post',
            $this->service,
            'registerSavePostHandler',
            10,
            2,
        ])->once();

        $this->plugin->loader->shouldReceive('addAction')->withArgs([
            'admin_footer',
            $this->service,
            'renderFooterScript',
            10,
            1,
        ])->once();

        $this->plugin->loader->shouldReceive('addFilter')->withArgs([
            'post_row_actions',
            $this->service,
            'addRowActions',
            10,
            2,
        ])->once();

        $this->service->register();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_adds_actions_to_rows_if_all_metadata_is_filled_in()
    {
        $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" aria-label="&#8220;Lege 1&#8221; snel bewerken">Snel&nbsp;bewerken</a>';

        WP_Mock::userFunction('get_post_meta', [
            'times' => 3,
            'return_in_order' => [
                '130',
                '120',
                '23-05-2018',
            ],
        ]);

        $this->service->setQuickEditHandlers();
        $actual = $this->service->addRowActions($actions, $this->post);
        $expected['inline hide-if-no-js'] = '<a href="#" data-new-price="130" data-price="120" data-active-date="23-05-2018" class="editinline" aria-label="&#8220;Lege 1&#8221; snel bewerken">Snel&nbsp;bewerken</a>';

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_adds_actions_to_rows_if_not_all_metadata_is_filled_in()
    {
        $actions['inline hide-if-no-js'] = '<a href="#" class="editinline" aria-label="&#8220;Lege 1&#8221; snel bewerken">Snel&nbsp;bewerken</a>';

        WP_Mock::userFunction('get_post_meta', [
            'times' => 3,
            'return_in_order' => [
                null,
                '120',
                '23-05-2018',
            ],
        ]);

        $this->service->setQuickEditHandlers();
        $actual = $this->service->addRowActions($actions, $this->post);
        $expected['inline hide-if-no-js'] = '<a href="#" data-price="120" data-active-date="23-05-2018" class="editinline" aria-label="&#8220;Lege 1&#8221; snel bewerken">Snel&nbsp;bewerken</a>';

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function if_returns_null_if_post_is_revision()
    {
        WP_Mock::userFunction('wp_is_post_revision', [
            'return' => true,
        ]);

        $actual = $this->service->registerSavePostHandler($this->post->ID, $this->post);
        $this->assertNull($actual);
    }

    /** @test */
    public function if_returns_null_if_post_is_autosave()
    {
        WP_Mock::userFunction('wp_is_post_autosave', [
            'return' => true,
        ]);

        $actual = $this->service->registerSavePostHandler($this->post->ID, $this->post);
        $this->assertNull($actual);
    }

    /** @test */
    public function if_returns_null_if_post_type_is_not_correctly()
    {
        $actual = $this->service->registerSavePostHandler($this->post->ID, $this->post);

        $this->assertNull($actual);
    }

    /** @test */
    public function if_returns_null_if_user_cannot_edit_post()
    {
        $this->post->post_type = 'pdc-leges';

        WP_Mock::userFunction('current_user_can', [
            'return' => false,
        ]);

        $actual = $this->service->registerSavePostHandler($this->post->ID, $this->post);

        $this->assertNull($actual);
    }

    /** @test */
    public function if_returns_null_if_all_the_checks_pass()
    {
        $this->post->post_type = 'pdc-leges';

        WP_Mock::userFunction('current_user_can', [
            'return' => true,
        ]);

        $this->service->setQuickEditHandlers();

        $_POST['_pdc-lege-price'] = '10';

        WP_Mock::userFunction('update_post_meta', [
            'times' => 1,
            'return' => true,
        ]);

        $actual = $this->service->registerSavePostHandler($this->post->ID, $this->post);

        $this->assertNull($actual);
    }
}
