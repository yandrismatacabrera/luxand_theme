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
        \OY\Plan\Api\PlanRepositoryInterface $planRepository
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;

        $this->customerRepository=$customerRepository;
        $this->registryFactory=$registryFactory;
        $this->registryRepository=$registryRepository;
        $this->timezone=$timezone;
        $this->collectionPlanFactory=$collectionPlanFactory;
        $this->planRepository=$planRepository;
    }


    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $data=[];

        if($this->getRequest()->getParam('customer_id'))
        {

            try{

                $collection = $this->collectionPlanFactory->create();
                $collection->addFieldToFilter('customer_id',(int)$this->getRequest()->getParam('customer_id'));
                $hasPlan = false;

                if($collection->getSize()){

                    foreach ($collection as $plan){

                        if($this->statusPlan($plan->getData('from'), $plan->getData('to'))){

                           if($plan->getData('access_number')){

                               if($plan->getData('access_enabled')){

                                   $plan->setData('access_enabled', (int)$plan->getData('access_enabled')-1);
                                   $this->planRepository->save($plan);
                                   $hasPlan = true;
                               }

                           }else{
                               $hasPlan = true;
                           }

                        }
                    }
                }

                if(!$hasPlan){

                    $data["success"]=false;
                    $data["msg"]="El usuario no tiene plan asociado.";
                    return $result->setData($data);
                }

                $customer = $this->customerRepository->getById((int)$this->getRequest()->getParam('customer_id'));
                $fullName = $customer->getFirstname().' '.$customer->getLastname();
                //$dateTime = $this->timezone->date()->format('Y-m-d H:i:s');
                $dateTime=date("Y-m-d H:i:s");

                $registry = $this->registryFactory->create();
                $registry->setCustomerId((int)$this->getRequest()->getParam('customer_id'));
                $registry->setDateTime($dateTime);
                $registry->setFullname($fullName);

                $this->registryRepository->save($registry);

                $data["success"]=true;
                $data["msg"]="Registro satisfactorio.";


            }catch (Exception $e){

                $data["success"]=false;
                $data["msg"]="El usuario no existe.";
                return $result->setData($data);
            }
        }

        return $result->setData($data);
    }

    public function statusPlan($from, $to){

        $today =date("Y-m-d H:i:s");

        if(strtotime($from) <= strtotime($today) && strtotime($to) >= strtotime($today))
            return true;

        return false;
    }


}

