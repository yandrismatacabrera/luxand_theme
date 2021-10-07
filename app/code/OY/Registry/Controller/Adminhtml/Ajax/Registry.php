<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03/12/20
 * Time: 09:53 AM
 */
namespace OY\Registry\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use OY\Registry\Api\RegistryRepositoryInterface;

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
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Customer\Model\Session $customerSession
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
        $this->directoryList=$directoryList;
        $this->customerSession=$customerSession;
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
            'image' => $customer->getCustomAttribute('photo')? $customer->getCustomAttribute('photo')->getValue() : '',
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

        if ($collection->getSize()) {
            foreach ($collection as $plan) {
                if ($this->statusPlan($plan->getData('from'), $plan->getData('to'))) {

                    if($plan->getData('hour_from') && $plan->getData('hour_to')){
                        if(!$this->restrictedTime($plan->getData('hour_from'), $plan->getData('hour_to'))){
                            continue;
                        }
                    }

                    if ($plan->getData('access_number')) {
                        if ($plan->getData('access_enabled')) {
                            $plan->setData('access_enabled', (int) $plan->getData('access_enabled') - 1);
                            $this->planRepository->save($plan);
                            $planData['from'] = $plan->getData('from');
                            $planData['to'] = $plan->getData('to');
                            $planData['access_enabled'] = $plan->getData('access_enabled');
                            $planData['access_number'] = $plan->getData('access_number');
                            $planData['restricted_from'] = $plan->getData('hour_from');
                            $planData['restricted_to'] = $plan->getData('hour_to');
                            $planData['success'] = true;
                            unset($planData['msg']);
                        }
                    } else {
                        $planData['from'] = $plan->getData('from');
                        $planData['to'] = $plan->getData('to');
                        $planData['access_enabled'] = $plan->getData('access_enabled');
                        $planData['access_number'] = $plan->getData('access_number');
                        $planData['restricted_from'] = $plan->getData('hour_from');
                        $planData['restricted_to'] = $plan->getData('hour_to');
                        $planData['success'] = true;
                        unset($planData['msg']);
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
        $customer = null;
        $customerId = $this->getRequest()->getParam('customer_id');
        $ci = $this->getRequest()->getParam('ci');

        if($this->customerSession->getCustomerId()){
            $ci = (int)$this->customerSession->getCustomerId();
        }

        $image = $this->getRequest()->getParam('image');

        $registryRecord = $this->registryFactory->create();
        $registryRecord->setDateTime(date("Y-m-d H:i:s"));
        if ($image) {
            $registryRecord->setPhoto($this->savePhoto($image));
        }

        $score = $this->getRequest()->getParam('score')??'';
        $registryRecord->setData('score',$score);

        if (!$customerId && !$ci) {
            $data['success'] = false;
            $data['msg'] = 'No se pudo identificar o no se tiene foto del cliente.';
            $registryRecord->setMessage($data['msg']);
            $registryRecord->setMethod(RegistryRepositoryInterface::METHOD_FACE);
            $this->registryRepository->save($registryRecord);
            return $result->setData($data);
        }

        $method = '';
        if ($customerId) {
            $customer = $this->getCustomerDataById((int)$customerId);
            $method = RegistryRepositoryInterface::METHOD_FACE;
        } elseif ($ci) {
            $customer = $this->getCustomerDataByCi($ci);
            $method = RegistryRepositoryInterface::METHOD_CI;
        }

        if ($customer->getCustomAttribute('client_local_access') && !$customer->getCustomAttribute('client_local_access')->getValue()) {
            $data['success'] = false;
            $data['msg'] = 'El cliente no se puede registrar porque no tiene acceso al local.';
            return $result->setData($data);
        }

        $data['customer'] = $this->formatCustomerData($customer);
        $data['plan'] = $this->getPlanDataByCustomer($customer);
        $data['book'] = $this->getBookDataByCustomer($customer);

        $registryRecord->setMethod($method);
        if (isset($data['customer']['success']) && $data['customer']['success'] &&
           isset($data['plan']['success']) && $data['plan']['success'] &&
           isset($data['book']['success']) && $data['book']['success']) {
            $data['success']=true;
            $registryRecord->setCustomerId($customerId);
            $registryRecord->setFullname($customer->getFirstname() . ' ' . $customer->getLastname());
            $registryRecord->setValid(1);
        } else {
            $data['success']=false;
            $registryRecord->setValid(0);
            if ($data['customer'] && $data['customer']['success']) {
                $registryRecord->setCustomerId($customerId);
                $registryRecord->setFullname($customer->getFirstname() . ' ' . $customer->getLastname());
            }
        }
        $formatedData = $this->formatResponse($data);
        if (isset($formatedData['success']) && $formatedData['success'] == false && isset($formatedData['msg'])) {
            $registryRecord->setMessage($formatedData['msg']);
        }
        $this->registryRepository->save($registryRecord);

        return $result->setData($formatedData);
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

        if (/*strtotime($from) <= strtotime($today) &&*/ strtotime($to) >= strtotime($today)) {
            return true;
        }

        return false;
    }

    public function restrictedTime($restrictedFrom, $restrictedTo){
        if($restrictedFrom && $restrictedTo){

            $fromTime = str_replace(':','',$restrictedFrom);
            $toTime = (int)str_replace(':','',$restrictedTo);
            $currentTime = (int)str_replace(':','',$this->timezone->date()->format('H:i'));

            if($currentTime < $fromTime || $currentTime > $toTime){
                return false;
            }
            return true;
        }
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

    private function savePhoto($image) {
        $mediaDir = $this->directoryList->getPath('media');

        if (!is_dir($mediaDir.'/customer/luxand/unidentified')) {
            mkdir($mediaDir.'/customer/luxand/unidentified', 0777, true);
        }

        $photoId = rand(100000000, 999999999);
        $imgName='img'.$photoId.'.png';

        file_put_contents($mediaDir.'/customer/luxand/unidentified/'.$imgName, base64_decode($image));
        return '/customer/luxand/unidentified/'.$imgName;
    }
}
