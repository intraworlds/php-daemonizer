<?php
namespace IW;

use RuntimeException;

class Daemonizer
{

    private static $stdin;
    private static $stdout;
    private static $stderr;

    /**
     * Daemonize current process
     *
     * @param callback|null $onParent optional callback to do in parent process before exit
     * @param array         $options  options
     *
     * @return void
     */
    public static function daemonize(callable $onParent=null, array $options=[]) {
        if (($pid = pcntl_fork()) == -1) {
             new RuntimeException('Could not fork');
        } elseif ($pid) {
            if ($onParent) {
                $onParent($pid);
            }

            exit;
        } else {
            // setup this process as session leader
            posix_setsid();

            // close FDs from parent request
            fclose(STDIN);
            fclose(STDOUT);
            fclose(STDERR);

            // reopen FDs to /dev/null or given file
            self::$stdin  = fopen('/dev/null', 'r');
            self::$stdout = fopen($options['stdout'] ?? '/dev/null', 'a+');
            self::$stderr = fopen($options['stderr'] ?? '/dev/null', 'a+');
        }
    }

}
