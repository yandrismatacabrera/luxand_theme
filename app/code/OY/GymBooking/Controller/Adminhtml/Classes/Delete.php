<?php
namespace OY\GymBooking\Controller\Adminhtml\Classes;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OY\GymBooking\Api\GymClassRepositoryInterface;
use OY\GymBooking\Model\GymClassFactory;
use Magento\Customer\Model\Session;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    protected $_coreRegistry = null;

    protected $gymClassRepository;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        GymClassRepositoryInterface $gymClassRepository,
        GymClassFactory $gymClassFactory,
        Session $customerSession
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->gymClassRepository = $gymClassRepository;
        $this->gymClassFactory=$gymClassFactory;
        $this->customerSession=$customerSession;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $model = $this->gymClassRepository->deleteById($id);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This class no longer exists.'));
                $this->_redirect('*/classes/');
                return;
            }
        }

        $this->_redirect('*/classes/');
    }
    protected function _isAllowed()
    {
        return true;
    }
}
