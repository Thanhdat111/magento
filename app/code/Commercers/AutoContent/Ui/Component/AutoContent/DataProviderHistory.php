<?php
/**
 * Hieund
 */
namespace Commercers\AutoContent\Ui\Component\AutoContent;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Commercers\AutoContent\Model\AutoContentProcessLog;

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
        AutoContentProcessLog $autocontentProcessLogCollection,
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
        $this->collection = $autocontentProcessLogCollection;
        $this->name = $name;
    }

    public function getData()
    {
        $this->_coreSession->start();
        $id = $this->_coreSession->getMessage();
        $autoContentProcessLog = $this->collection->getCollection()->addFieldToFilter('id_profiler', array('eq' => $id));
        $autoContentProcessLog->getData();
        return $autoContentProcessLog;
    }
}
