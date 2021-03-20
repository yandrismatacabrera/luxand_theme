<?php

namespace OY\Customer\Controller\Adminhtml\Index;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Address\Mapper;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\DataObjectFactory as ObjectFactory;

class AddPlan extends \Magento\Customer\Controller\Adminhtml\Index implements HttpGetActionInterface
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Customer\Model\Metadata\FormFactory $formFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Helper\View $viewHelper,
        \Magento\Framework\Math\Random $random,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        Mapper $addressMapper,
        AccountManagementInterface $customerAccountManagement,
        AddressRepositoryInterface $addressRepository,
        CustomerInterfaceFactory $customerDataFactory,
        AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        ObjectFactory $objectFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionProductFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $customerFactory,
            $addressFactory,
            $formFactory,
            $subscriberFactory,
            $viewHelper,
            $random,
            $customerRepository,
            $extensibleDataObjectConverter,
            $addressMapper,
            $customerAccountManagement,
            $addressRepository,
            $customerDataFactory,
            $addressDataFactory,
            $customerMapper,
            $dataObjectProcessor,
            $dataObjectHelper,
            $objectFactory,
            $layoutFactory,
            $resultLayoutFactory,
            $resultPageFactory,
            $resultForwardFactory,
            $resultJsonFactory
        );
        $this->_storeManager = $storeManager;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->orderService = $orderService;
        $this->collectionProductFactory=$collectionProductFactory;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $collection = $this->collectionProductFactory->create();
        $collection->addFieldToFilter('entity_id', $this->_request->getParam('id_product'));

        $customer= $this->_customerRepository->getById((int)$this->_request->getParam('id'));

        $addresses = $customer->getAddresses();
        if (!count($addresses)) {
            $this->messageManager->addErrorMessage('El Usuario no tiene direccion.');
            $resultRedirect->setPath('customer/index/index');
            return $resultRedirect;
        }

        $addressBilling = $addresses[0]->__toArray();
        $addressShipping = $addresses[0]->__toArray();
        foreach ($addresses as $address) {
            $data = $address->__toArray();
            if (isset($data['default_billing']) && $data['default_billing']) {
                $addressBilling = $data;
            }
            if (isset($data['default_shipping']) && $data['default_shipping']) {
                $addressShipping = $data;
            }
        }

        $options=[];
        if ($collection->getSize()) {
            $product = $collection->getFirstItem();
            $productRepo = $this->productRepository->getById($collection->getFirstItem()->getId());

            foreach ($productRepo->getOptions() as $opt)
            {
                if($opt->getType() == 'date'){
                    $options[$opt->getOptionId()] = array('date'=>date("d/m/Y"));
                }
            }

        } else {
            $this->messageManager->addErrorMessage('No existe plan ' . $this->_request->getParam('type'));
            $resultRedirect->setPath('customer/index/index');
            return $resultRedirect;
        }

        $store = $this->_storeManager->getStore(1);
        $quote=$this->quote->create();
        $quote->setStore($store);

        $quote->setCurrency();
        $quote->assignCustomer($customer);
        $product->setPrice($productRepo->getPrice());

        $obj = $this->_objectFactory->create();
        $obj->setData('options', $options);
        $obj->setData('qty', 1);

        $quote->addProduct(
            $product,
            $obj
        );

        $quote->getBillingAddress()->addData($addressBilling);
        $quote->getShippingAddress()->addData($addressShipping);

        $quote->setPaymentMethod('checkmo');
        $quote->setInventoryProcessed(false);
        $quote->collectTotals();
        $quote->save();

        $quote->getPayment()->importData(['method' => 'checkmo']);
        $quote->collectTotals()->save();

        $order = $this->quoteManagement->submit($quote);

        if ($order->getId()) {
            $resultRedirect->setPath('sales/order/view/order_id/' . $order->getId());
        } else {
            $resultRedirect->setPath('customer/index/index');
        }
        return $resultRedirect;
    }
}
