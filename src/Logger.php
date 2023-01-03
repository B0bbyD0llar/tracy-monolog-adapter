<?php

/**
 * @license    New BSD License
 * @link       https://github.com/nextras/tracy-monolog-adapter
 */

namespace Nextras\TracyMonologAdapter;

use Monolog;
use Throwable;
use Tracy\Helpers;
use Tracy\ILogger;

class Logger implements ILogger
{
    /** @const Tracy priority to Monolog priority mapping */
    protected const PRIORITY_MAP = [
        self::DEBUG => Monolog\Level::Debug,
        self::INFO => Monolog\Level::Info,
        self::WARNING => Monolog\Level::Warning,
        self::ERROR => Monolog\Level::Error,
        self::EXCEPTION => Monolog\Level::Critical,
        self::CRITICAL => Monolog\Level::Critical,
    ];

    /** @var Monolog\Logger */
    protected Monolog\Logger $monolog;

    /**
     * @param Monolog\Logger $monolog
     */
    public function __construct(Monolog\Logger $monolog)
    {
        $this->monolog = $monolog;
    }

    public function log(mixed $message, mixed $priority = self::INFO): void
    {
        $context = [
            'at' => Helpers::getSource(),
        ];

        if ($message instanceof Throwable) {
            $context['exception'] = $message;
            $message = '';
        }

        $this->monolog->addRecord(
            self::PRIORITY_MAP[$priority] ?? Monolog\Level::Error,
            $message,
            $context
        );
    }
}
