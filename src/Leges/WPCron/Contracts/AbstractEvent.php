<?php

namespace OWC\PDC\Leges\WPCron\Contracts;

abstract class AbstractEvent
{
    public static function init(): void
    {
        (new static())->execute();
    }

    abstract protected function execute(): void;

    protected function logError(string $message): void
    {
        if (! defined('WP_DEBUG') || ! WP_DEBUG) {
            return;
        }

        error_log(sprintf('OWC PDC Leges: %s', $message));
    }
}
