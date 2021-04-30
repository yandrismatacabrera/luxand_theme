<?php
namespace OY\GymBooking\Controller\Adminhtml\Classes;


use Magento\Backend\App\Action;

class NewAction extends Action
{
    protected $resultForwardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
    }

    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('edit');
        return $resultForward;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
