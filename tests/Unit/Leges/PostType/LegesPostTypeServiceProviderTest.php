<?php

namespace OWC\PDC\Leges\Tests\PostType;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Leges\PostType\LegesPostTypeServiceProvider;
use OWC\PDC\Leges\Tests\Unit\TestCase;
use WP_Mock;

class LegesPostTypeServiceProviderTest extends TestCase
{
    /**
     * @var
     */
    protected $service;

    /**
     * @var
     */
    protected $plugin;

    protected string $prefix = '_pdc-lege';
    protected string $postType = 'pdc-leges';

    public function setUp(): void
    {
        Parent::setUp();

        $config = m::mock(Config::class);
        $this->plugin = m::mock(Plugin::class);

        $this->plugin->config = $config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->service = new LegesPostTypeServiceProvider($this->plugin);
    }

    public function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_posttype()
    {
        $this->plugin->loader->shouldReceive('addAction')->withArgs([
            'init',
            $this->service,
            'registerPostType',
        ])->once();

        $this->plugin->loader->shouldReceive('addFilter')->withArgs([
            "ext-cpts/{$this->postType}/filter-query/{$this->prefix}_post_id",
            $this->service,
            'filterByPostId',
            10,
            3,
        ])->once();

        $this->service->register();

        // Is in place for passing this test when Mockery is not returning errors.
        $this->assertTrue(true);
    }
}
