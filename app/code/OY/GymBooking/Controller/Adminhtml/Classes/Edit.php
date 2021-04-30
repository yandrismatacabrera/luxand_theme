<?php
namespace OY\GymBooking\Controller\Adminhtml\Classes;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OY\GymBooking\Api\GymClassRepositoryInterface;
use OY\GymBooking\Model\GymClassFactory;
use Magento\Customer\Model\Session;

class Edit extends \Magento\Backend\App\Action
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
                $model = $this->gymClassRepository->getById($id);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This class no longer exists.'));
                $this->_redirect('*/classes/');
                return;
            }
        } else {
            $model = $this->gymClassFactory->create();
        }

        $this->customerSession->setGymclass($model);
        $this->_coreRegistry->register('current_gym_class', $model);

        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'OY_GymBooking::gymbooking'
        )->_addBreadcrumb(
            __('Class Success'),
            __('Manage Class')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage Class'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getSupplierName() : __('Nueva Clase')
        );

        $breadcrumb = $id ? __('Edit Clase') : __('Nueva Clase');
        $this->_addBreadcrumb($breadcrumb, $breadcrumb);
        $this->_view->renderLayout();
    }
    protected function _isAllowed()
    {
        return true;
    }
}
