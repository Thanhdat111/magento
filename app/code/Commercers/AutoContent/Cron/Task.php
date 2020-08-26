<?php

namespace Commercers\AutoContent\Cron;
class Task {
    public function __construct(
        \Commercers\AutoContent\Service\Process $process
    ){
        $this->process = $process;
    }
    public function execute($schedule) {
        $jobCode = $schedule->getJobCode();
        $this->process->run($jobCode);
    }

}
