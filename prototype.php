<?php
        #     log.debug "Starting worker... '#{@cmd}'"
        #     log.debug "... with environment: vi=#{version_info}, cid=#{customer_id}, jobs=#{jobs}"
        #     r, w = IO.pipe                                  # open pipe between child process and this process
        #     begin
        #         opid = fork do                              # helpful subprocess which will fork itself and call a command
        #             r.close                                 # don't need read anything
        #             pid = fork do                           # create subprocess for running actual command
        #                 STDIN.reopen '/dev/null'            # ignore parent's input
        #                 STDOUT.reopen IW::Holly.application.appctx[:job_stdout], 'a' # redirect standard output
        #                 STDERR.reopen IW::Holly.application.appctx[:job_stderr], 'a' # redirect standard error
        #                 Process.setsid                      # detach from parent session (establishes this process as a new session and process group leader)

        #                 # TODO [Ondrej Esler, A] better impl
        #                 ENV['VERSION_INFO'] = self.version_info
        #                 ENV['CUSTOMER_ID']  = self.customer_id
        #                 ENV['JOBS']         = self.jobs.join(',')

        #                 exec(@cmd)                          # run given command like a daemon
        #             end
        #             w.write(pid.to_s)                       # send pid back to forker
        #             exit!(0)                                # we don't need helpful subprocess anymore
        #         end
        #         w.close                                     # we don't need write on this side
        #         Process.waitpid(opid, 0)                    # wait for helpful subprocess
        #         @pid = r.read.chomp.to_i
        #         @process_start_at = Time.now.to_f
        #         log.debug "... started #{self}"
        #     ensure # make sure the file descriptors get closed no matter what
        #         r.close rescue nil
        #         w.close rescue nil
        #     end

// list($w, $r) = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
$pid     = pcntl_fork();

if ($pid == -1) {
     die('could not fork');

} else if ($pid) {
    echo $pid;
    exit;

} else {
posix_setsid();

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);
$stdin  = fopen('/dev/null', 'r');
$stdout = fopen('/tmp/stdout', 'a+');
$stderr = fopen('/tmp/stderr', 'a+');

    while (true) {
        echo '.';
        error_log('.');
        sleep(1);
    }
}
