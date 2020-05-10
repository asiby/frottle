<?php

namespace ASiby;

use InvalidArgumentException;

class Frottle
{
    /**
     * Throttles the execution of a function call.
     *
     * @param   callable  $callback         A callable
     * @param   integer   $throttleSeconds  Prevents calling a function $func unless this number of seconds has elapsed
     * @param   array     $args
     *
     * @return  ThrottleResult
     * @since   1.0.0
     */
    static public function throttle($callback, $throttleSeconds, $args = [])
    {
        if (!is_callable($callback))
        {
            throw new InvalidArgumentException("The first argument of the throttle function musts be a callable", 500);
        }

        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $callableId = "{$backtrace[0]['file']}@{$backtrace[0]['line']}";

        static $lastCallTime = [];

        $now = time();

        if (!($lastCallTime[$callableId] ?? false) || ($now - $lastCallTime[$callableId]) >= $throttleSeconds)
        {
            $lastCallTime[$callableId] = $now;

            return new ThrottleResult(call_user_func($callback, ...$args));
        }

        return new ThrottleResult(null, true);
    }
}