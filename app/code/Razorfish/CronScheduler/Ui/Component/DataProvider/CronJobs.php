<?php

declare(strict_types=1);

namespace Razorfish\CronScheduler\Ui\Component\DataProvider;

use Magento\Cron\Model\ConfigInterface as CronConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as UiDataProvider;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class CronJobs extends UiDataProvider
{
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var ReportingInterface
     */
    protected $reporting;
    protected $meta = [];
    protected $data = [];

    /**
     * @param array<int, array<int, string>> $meta
     * @param array<int, array<int, string>> $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        // custom parameters
        protected CronConfig           $cronConfig,
        protected ScopeConfigInterface  $scopeConfig,
        protected StoreManagerInterface $storeManager,
        // base parameters
        string                          $name,
        string                          $primaryFieldName,
        string                          $requestFieldName,
        ReportingInterface              $reporting,
        SearchCriteriaBuilder           $searchCriteriaBuilder,
        RequestInterface                $request,
        FilterBuilder                   $filterBuilder,
        array                           $meta = [],
        array                           $data = [],
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
//            $items = [];
//            for( $i = 1; $i <= 100; $i++){
//                $items[] = ['id' => $i, 'name' => $this->generateRandomString(5), 'company' => $this->generateRandomString(8), 'city' => $this->generateRandomString(10)];
//            }
            $items = $this->getCronConfig();

//            print_r($this->request->getParam('filters'));
//            $f = $this->filterBuilder->create();
//            $f->setField('name');
//            $f->setValue('currency');
//            $this->addFilter($f);
//            print_r($this->getSearchCriteria());

            $pagesize = intval($this->request->getParam('paging')['pageSize'] ?? 20);
            $pageCurrent = intval($this->request->getParam('paging')['current'] ?? 1);
            $pageoffset = ($pageCurrent - 1)*$pagesize;

            return [
                'totalRecords' => count($items),
                'items' => array_slice($items, $pageoffset, $pagesize),
            ];
        } catch (LocalizedException $e) {
            return [
                'items' => [],
                'error' => 'Server Error: Please contact the administrator if it persists !!',
            ];
        }
    }

    public function getCronConfig(){
        $allJobs = [];
        if ( !empty($this->cronConfig->getJobs()) ){
            foreach ($this->cronConfig->getJobs() as $group => $cronJobs) {
                foreach ($cronJobs as $cod => $cronJob) {
                    $cronJob['group'] = $group;
                    $this->populateCronjob($cronJob);
                    $allJobs[] = $cronJob;
                }
            }
        }
        return $allJobs;
    }

    public function populateCronjob(&$cronJob){
        if ( empty($cronJob['schedule']) && !empty($cronJob['config_path']) ){
            $cronJob['schedule'] = 'FR : '.$this->scopeConfig->getValue($cronJob['config_path'], \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }
        if ( empty($cronJob['schedule']) && $cronJob['method'] == 'execute' ){
            $cronJob['schedule'] = __('LABEL_NOT_SCHEDULED');
        }
    }


//    public function parseCrontab($schedule){
//        $cronPeriods = ['minute', 'hour', 'day', 'month', 'weekday'];
//        $cronExploded = explode(' ', $schedule);
//        foreach ($cronPeriods as $i => $period) {
//            $value = $cronExploded[$i] ?? '';
//        }
//
//        *	any value
//        ,	value list separator
//        -	range of values
//        /	step values
//        @yearly	(non-standard)
//        @annually	(non-standard)
//        @monthly	(non-standard)
//        @weekly	(non-standard)
//        @daily	(non-standard)
//        @hourly	(non-standard)
//        @reboot	(non-standard)
//     }

    // ###########################################

    public function setLimit($offset, $size)
    {
    }

//    public function addOrder($field, $direction)
//    {
//    }
//
//    public function addFilter(\Magento\Framework\Api\Filter $filter)
//    {
//    }
}
