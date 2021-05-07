<?php

namespace OY\Routine\Controller\Adminhtml\Series;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute() {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('OY_Routine::series');
        $resultPage->getConfig()->getTitle()->prepend((__('Serie de Ejercicios')));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OY_Routine::series');
    }
}