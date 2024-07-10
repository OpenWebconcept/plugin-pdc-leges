<?php

namespace OWC\PDC\Leges\Tests\WPCron;

use Mockery as m;
use OWC\PDC\Leges\Tests\Unit\TestCase;
use OWC\PDC\Leges\WPCron\Events\UpdateLegesPrices;
use WP_Mock;

class UpdateLegesPricesTest extends TestCase
{
    /** @test */
    public function lege_active_date_should_update_correct_format()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => '03-05-2022',
        ]);

        $actual = (new UpdateLegesPrices())->shouldUpdate(1);
        $expected = true;

        $this->assertEquals($actual, $expected);
    }

    /** @test */
    public function lege_active_date_should_not_update_wrong_format()
    {
        WP_Mock::userFunction('get_post_meta', [
            'return' => '2022-03-05',
        ]);

        m::mock('alias:WP_CLI')->shouldReceive('warning')->andReturnArg(0);

        $actual = (new UpdateLegesPrices())->shouldUpdate(1);
        $expected = false;

        $this->assertEquals($actual, $expected);
    }

    /** @test */
    public function lege_active_date_empty_should_not_update()
    {
        WP_Mock::userFunction('get_post_meta', [
            'args' => [1, '_pdc-lege-active-date', true],
            'return' => '',
        ]);


        $actual = (new UpdateLegesPrices())->shouldUpdate(1);
        $expected = false;

        $this->assertEquals($actual, $expected);
    }
}
