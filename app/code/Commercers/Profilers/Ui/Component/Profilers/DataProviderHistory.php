<?php
/**
 * Hieund
 */
namespace Commercers\Profilers\Ui\Component\Profilers;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Commercers\Profilers\Model\ProfilersProcessLog;

class DataProviderHistory extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    protected $_coreSession;

    public function __construct(
        $name,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        ProfilersProcessLog $profilersProcessLogCollection,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            '',
            '',
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->_coreSession = $coreSession;
        $this->collection = $profilersProcessLogCollection;
        $this->name = $name;
    }

    public function getData()
    {
        $this->_coreSession->start();
        $id = $this->_coreSession->getProfilerId();
        $profilersProcessLog = $this->collection->getCollection()->addFieldToFilter('id_profiler', array('eq' => $id));
        $profilersProcessLog->getData();
        return $profilersProcessLog;
    }
}
