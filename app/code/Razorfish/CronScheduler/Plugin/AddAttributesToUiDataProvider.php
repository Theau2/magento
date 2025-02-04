<?php
/**
 * Copyright [first year code created] Adobe
 * All rights reserved.
 */

namespace Razorfish\CronScheduler\Plugin;

use Razorfish\CronScheduler\Ui\DataProvider\CronJobs\ListingDataProvider as CronJobsDataProvider;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class AddAttributesToUiDataProvider
{
    /**
     * Constructor
     *
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        protected AttributeRepositoryInterface $attributeRepository
    ) {}

    /**
     * Get Search Result after plugin
     *
     * @param CronJobsDataProvider $subject
     * @param SearchResult $result
     * @return SearchResult
     */
//    public function afterGetSearchResult(CronJobsDataProvider $subject, SearchResult $result)
//    {
//        if ($result->isLoaded()) {
//            return $result;
//        }
//
//        $column = 'entity_id';
//
//        $attribute = $this->attributeRepository->get('catalog_category', 'name');
//
//        $result->getSelect()->joinLeft(
//            ['devgridname' => $attribute->getBackendTable()],
//            'devgridname.' . $column . ' = main_table.' . $column . ' AND devgridname.attribute_id = '
//            . $attribute->getAttributeId(),
//            ['name' => 'devgridname.value']
//        );
//
//        $result->getSelect()->where('devgridname.value LIKE "B%"');
//
//        return $result;
//    }
}
