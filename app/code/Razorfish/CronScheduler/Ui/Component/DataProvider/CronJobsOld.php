<?php
/**
 * Copyright [first year code created] Adobe
 * All rights reserved.
 */

namespace Razorfish\CronScheduler\Ui\Component\DataProvider;

use Magento\Cron\Model\ConfigInterface as CronConfig;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class CronJobsOld extends DataProvider
{

    /**
     * Construct
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        // custom parameters
        protected CronConfig $cronConfig,
        protected ScopeConfigInterface $scopeConfig,
        protected StoreManagerInterface $storeManager,
        // base parameters for DataProvider
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $reporting, $searchCriteriaBuilder, $request, $filterBuilder, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $items = $this->getCronConfig();

        $pagesize = intval($this->request->getParam('paging')['pageSize'] ?? 20);
        $pageCurrent = intval($this->request->getParam('paging')['current'] ?? 1);
        $pageoffset = ($pageCurrent - 1)*$pagesize;

        return [
            'totalRecords' => count($items),
            'items' => array_slice($items, $pageoffset, $pagesize ),
        ];
    }

    public function getCronConfig(){
        $i = 1;
        $page_id = 1;
        $allJobs = [];
        if ( !empty($this->cronConfig->getJobs()) ){
            foreach ($this->cronConfig->getJobs() as $group => $cronJobs) {
                foreach ($cronJobs as $cod => $cronJob) {
                    $cronJob['group'] = $group;
//                    $cronJob['job_code'] = $cod;
//                    $cronJob['ids'] = $i;
                    $cronJob['id'] = $i;
//                    $cronJob['page_id'] = $page_id;
                    $this->populateCronjob($cronJob);
                    $allJobs[$cod] = $cronJob;
                    $i++;
                    if ( $i % ($this->request->getParam('paging')['pageSize'] ?? 20) === 1 ){
                        $page_id++;
                    }
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

}
