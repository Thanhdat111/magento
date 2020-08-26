<?php

namespace Commercers\Profilers\Service;

class Log {

    protected $_profilersProcessLogFactory;

    public function __Construct(
    \Commercers\Profilers\Model\ProfilersProcessLogFactory $profilersProcessLogFactory
    ) {
        $this->_profilersProcessLogFactory = $profilersProcessLogFactory;
    }

    public function execute($idProfiler) {
        $profilersProcessLog = $this->_profilersProcessLogFactory->create();
        $profilersProcessLog->addData([
            'id_profiler' => $idProfiler
        ])->save();
        return $profilersProcessLog;
    }

    public function executeAfter($idProcess, $status, $message) {
        $profilersProcessLog = $this->_profilersProcessLogFactory->create();
        $profilersProcessLog->load($idProcess);
        $profilersProcessLog->addData([
            'status' => $status,
            'message' => $message,
            'end_at' => date('Y-m-d H:i:s')
        ])->save();
    }

}
