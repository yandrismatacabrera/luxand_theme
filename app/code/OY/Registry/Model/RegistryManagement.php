<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/20
 * Time: 02:26 PM
 */
namespace OY\Registry\Model;

use mysql_xdevapi\Exception;
use OY\Registry\Api\RegistryManagementInterface;

class RegistryManagement implements RegistryManagementInterface
{

    public function __construct (
        \Magento\Framework\Webapi\Rest\Request $request,
        \OY\Registry\Helper\Luxand $luxand,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \OY\Registry\Model\RegistryFactory $registryFactory,
        \OY\Registry\Api\RegistryRepositoryInterface $registryRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \OY\Registry\Helper\Registry $registry,
        \Magento\Customer\Model\Session $customerSession
    ) {

        $this->request         = $request;
        $this->luxand         = $luxand;
        $this->directoryList=$directoryList;
        $this->storeManager=$storeManager;
        $this->customerRepository=$customerRepository;
        $this->registryFactory=$registryFactory;
        $this->registryRepository=$registryRepository;
        $this->timezone=$timezone;
        $this->registry=$registry;
        $this->customerSession=$customerSession;
    }

    public function recognitionFace()
    {

        $param = $this->request->getBodyParams();

        return $this->luxand->callRecognition($param['image']);
    }

    public function createCustomer()
    {
        $param = $this->request->getBodyParams();

        if(isset($param['customer_id'])){

            $customer = $this->customerRepository->getById((int)$param['customer_id']);
        }

        $imgContent64Base = $param['image']['base64_encoded_data'];
        $imgName = $param['image']['name'];
        $imgType = $param['image']['type'];
        $media_dir = $this->directoryList->getPath('media');

        $data = 'data:'.$imgType.';base64,'.$imgContent64Base;

        file_put_contents($media_dir.'/customer/'.$imgName, base64_decode($data));

        $imagePub =$this->storeManager->getStore()->getBaseUrl().'media/'.$imgName;

        return $this->luxand->createCustomer($param['name'], $param['store'], $imagePub);
    }

    public function registryCustomer()
    {
        $param = $this->request->getBodyParams();

        if(isset($param['customer_id'])){
            $this->customerSession->setCustomerId($param['customer_id']);
        }
        $result = $this->registry->registry();
        print_r($result);die;
        return true;
    }


}
