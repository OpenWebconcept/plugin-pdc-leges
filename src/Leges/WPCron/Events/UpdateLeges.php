<?php

namespace OWC\PDC\Leges\WPCron\Events;

class UpdateLeges
{
    public static function init(): void
    {
        (new self())->execute();
    }

    private function execute(): void
    {
        //
    }

}
