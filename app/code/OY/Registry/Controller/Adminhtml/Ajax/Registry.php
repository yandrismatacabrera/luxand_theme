<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03/12/20
 * Time: 09:53 AM
 */
namespace OY\Registry\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;

class Registry extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \OY\Registry\Model\RegistryFactory $registryFactory,
        \OY\Registry\Api\RegistryRepositoryInterface $registryRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \OY\Plan\Model\ResourceModel\Plan\CollectionFactory $collectionPlanFactory,
        \OY\Plan\Api\PlanRepositoryInterface $planRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Webkul\BookingSystem\Model\ResourceModel\Booked\CollectionFactory $bookedCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;

        $this->customerRepository=$customerRepository;
        $this->registryFactory=$registryFactory;
        $this->registryRepository=$registryRepository;
        $this->timezone=$timezone;
        $this->collectionPlanFactory=$collectionPlanFactory;
        $this->planRepository=$planRepository;
        $this->config=$config;
        $this->bookedCollectionFactory=$bookedCollectionFactory;
        $this->customerCollectionFactory=$customerCollectionFactory;
    }

    public function getCustomerDataByCi($ci)
    {
        try {
            $customerCollection = $this->customerCollectionFactory->create();
            $customerCollection->addAttributeToSelect('*')
            ->addAttributeToFilter('ci', $ci);
            if ($customerCollection->getSize()) {
                return $this->customerRepository->getById((int)$customerCollection->getFirstItem()->getId());
            }
        } catch (\Exception $e) {
        }
        return null;
    }

    public function getCustomerDataById($ci)
    {
        try {
            return $this->customerRepository->getById((int)$ci);
        } catch (\Exception $e) {
        }
        return null;
    }

    public function formatCustomerData($customer)
    {
        if (!$customer) {
            return [
                'success' => false,
                'msg' => 'Cliente no entontrado'
            ];
        }
        return [
            'success' => true,
            'id' => $customer->getId(),
            'image' => $customer->getCustomAttribute('photo')->getValue(),
            'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
            'email' => $customer->getEmail(),
            'ci' => $customer->getCustomAttribute('ci')->getValue()
        ];
    }

    public function getPlanDataByCustomer($customer)
    {
        if (!$customer) {
            return [];
        }
        $planData = [
            'success' => false,
            'msg' => 'El usuario no tiene plan asociado.'
        ];
        $collection = $this->collectionPlanFactory->create();
        $collection->addFieldToFilter('customer_id', $customer->getId());
        $hasPlan = false;

        if ($collection->getSize()) {
            foreach ($collection as $plan) {
                $this->log("Customer Plan => " . $customer->getId() . " - RESULT => " . print_r($plan->getData('from') . ' - ' . $plan->getData('to'), true));
                if ($this->statusPlan($plan->getData('from'), $plan->getData('to'))) {
                    if ($plan->getData('access_number')) {
                        if ($plan->getData('access_enabled')) {
                            $plan->setData('access_enabled', (int) $plan->getData('access_enabled') - 1);
                            $this->planRepository->save($plan);
                            $planData['plan'] = $plan;
                            $planData['success'] = true;
                            unset($planData['msg']);
                            $hasPlan = true;
                        }
                    } else {
                        $hasPlan = true;
                    }
                }
            }
        }
        return $planData;
    }

    public function getBookDataByCustomer($customer)
    {
        if (!$customer) {
            return [];
        }
        $bookingData = [
            'success' => true,
            'active' => false
        ];
        if ($this->isEnableReserve()) {
            $bookingData['active'] = true;
            $dateTimeBooked = date("d-m-Y");
            $bookedCollection = $this->bookedCollectionFactory->create();
            $bookedCollection->addFieldToFilter('booking_from', ['like' => '%' . $dateTimeBooked . '%']);
            $bookedCollection->addFieldToFilter('customer_id', $customer->getId());
            if ($bookedCollection->getSize()) {
                $booked = $bookedCollection->getFirstItem();
                $bookingData['msg'] = 'Reserva desde ' . $booked->getData('booking_from') . ' hasta ' . $booked->getData('booking_too');
                $bookingData['success'] = true;
            } else {
                $bookingData['msg'] = 'No tiene reserva para hoy.';
                $bookingData['success'] = false;
            }
        }
        return $bookingData;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $data = [];
        $customer;
        $customerId = $this->getRequest()->getParam('customer_id');
        $ci = $this->getRequest()->getParam('ci');

        if (!$customerId && !$ci) {
            $data['success'] = false;
            $data['msg'] = 'Se debe indicar ci o identificador de cliente.';
            return $result->setData($data);
        }

        if ($customerId) {
            $customer = $this->getCustomerDataById((int)$customerId);
        } elseif ($ci) {
            $customer = $this->getCustomerDataByCi($ci);
        }
        $data['customer'] = $this->formatCustomerData($customer);
        $data['plan'] = $this->getPlanDataByCustomer($customer);
        $data['book'] = $this->getBookDataByCustomer($customer);

        return $result->setData($this->formatResponse($data));
    }

    public function formatResponse($data)
    {
        if ($data && $data['customer'] && !$data['customer']['success']) {
            $data['success'] = false;
            $data['msg'] = $data['customer']['msg'];
            return $data;
        }
        if ($data && $data['plan'] && !$data['plan']['success']) {
            $data['success'] = false;
            $data['msg'] = $data['plan']['msg'];
            return $data;
        }
        if ($data && $data['book'] && !$data['book']['success']) {
            $data['success'] = false;
            $data['msg'] = $data['plan']['msg'];
            return $data;
        }
        return $data;
    }

    public function statusPlan($from, $to)
    {
        $today =date("Y-m-d H:i:s");
        $this->log("Customer Plan => RESULT TODAY => " . print_r($today, true));
        if (strtotime($from) <= strtotime($today) && strtotime($to) >= strtotime($today)) {
            return true;
        }

        return false;
    }

    private function getConfig($config_path)
    {
        return $this->config->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    private function isEnableReserve()
    {
        return $this->getConfig("reserve_general/config_general/enable");
    }

    public function log($mensaje)
    {
        $nombre='registry_' . date('Y_m');
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/' . $nombre . '.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($mensaje);
    }
}
