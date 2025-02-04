<?php
/**
 * Copyright [first year code created] Adobe
 * All rights reserved.
 */

namespace Razorfish\CronScheduler\Controller\Adminhtml\Jobs;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends Action implements HttpGetActionInterface
{
    public function __construct(
        Context $context,
        private readonly PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Razorfish_CronScheduler::title');
        $resultPage->addBreadcrumb(__('Cron Jobs'), __('Cron Jobs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Cron jobs'));
        return $resultPage;
    }
}
