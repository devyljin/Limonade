<?php

namespace Agrume\Limonade\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Level;
use Monolog\LogRecord;

final class ColoredFormatter extends LineFormatter
{
    private const LEVEL_COLORS = [
        Level::Debug->value     => '36',
        Level::Info->value      => '32',
        Level::Notice->value    => '32;1',
        Level::Warning->value   => '33',
        Level::Error->value     => '31',
        Level::Critical->value  => '31;1',
        Level::Alert->value     => '37;41',
        Level::Emergency->value => '37;41;1',
    ];

    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false,
        bool $includeStacktraces = false
    ) {
        parent::__construct(
            $format,
            $dateFormat,
            $allowInlineLineBreaks,
            $ignoreEmptyContextAndExtra,
            $includeStacktraces
        );
    }

    public function format(LogRecord $record): string
    {
        // Skip noisy apache “Closing” lines
        if (str_contains($record->message, 'Closing')) {
            return '';
        }

        $date    = $record->datetime->format('Y-m-d H:i:s');
        $channel = strtoupper($record->channel);
        $level   = $record->level;
        $color   = self::LEVEL_COLORS[$level->value] ?? '0';

        $context = $record->context
            ? json_encode(
                $record->context,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
            : '';

        $out = sprintf(
            "\033[90m%s\033[0m \033[%sm%s\033[0m \033[1;34m[%s]\033[0m %s",
            $date,
            $color,
            strtoupper($level->name),
            $channel,
            $record->message
        );

        if ($context !== '') {
            $out .= " \033[90m{$context}\033[0m";
        }

        return $out . PHP_EOL;
    }

    public function formatBatch(array $records): string
    {
        return array_reduce(
            $records,
            fn (string $carry, LogRecord $r) => $carry . $this->format($r),
            ''
        );
    }
}