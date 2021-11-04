<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04/12/20
 * Time: 12:14 AM
 */
namespace OY\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class RegistryCustomerLuxand implements ObserverInterface
{
    public function __construct(
        \OY\Registry\Helper\Luxand $luxand,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Message\ManagerInterface $manager,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->luxand         = $luxand;
        $this->directoryList=$directoryList;
        $this->storeManager=$storeManager;
        $this->customerRepository=$customerRepository;
        $this->manager=$manager;
        $this->messageManager = $context->getMessageManager();
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $this->customerRepository->getById($observer->getCustomer()->getId());

        $photoChanged = false;
        if ($customer->getCustomAttribute('photo') && $customer->getCustomAttribute('photo')->getValue()) {
            if (!$customer->getCustomAttribute('previous_photo') || !$customer->getCustomAttribute('previous_photo')->getValue()) {
                $photoChanged = true;
                $customer->setCustomAttribute('previous_photo', $customer->getCustomAttribute('photo')->getValue());
                $this->customerRepository->save($customer);
            } elseif ($customer->getCustomAttribute('photo')->getValue() != $customer->getCustomAttribute('previous_photo')->getValue()) {
                $customer->setCustomAttribute('previous_photo', $customer->getCustomAttribute('photo')->getValue());
                $this->customerRepository->save($customer);
                $photoChanged = true;
            }
        }

        if ($customer->getCustomAttribute('luxand_registry') &&
            $customer->getCustomAttribute('luxand_registry')->getValue() &&
            $customer->getCustomAttribute('luxand_id') &&
            $customer->getCustomAttribute('luxand_id')->getValue()) {
            if ($photoChanged == true) {
                if ($customer->getCustomAttribute('luxand_photo_id') && $customer->getCustomAttribute('luxand_photo_id')->getValue()) {
                    $this->luxand->deleteCustomerPhoto($customer->getCustomAttribute('luxand_id')->getValue(), $customer->getCustomAttribute('luxand_photo_id')->getValue());
                }
                $img = $customer->getCustomAttribute('photo')->getValue();
                $imagePub ='pub/media' . $img;
                $this->luxand->addCustomerPhoto($customer->getCustomAttribute('luxand_id')->getValue(), $imagePub);
                $faceId = $this->luxand->getCustomerFace($customer->getCustomAttribute('luxand_id')->getValue());
                $customer->setCustomAttribute('luxand_photo_id', $faceId);
                $this->customerRepository->save($customer);
            }

            return $this;
        }

        if (!$customer->getCustomAttribute('photo')) {
            return $this;
        }

        if (!$customer->getCustomAttribute('ci')) {
            return $this;
        }

        $img=$customer->getCustomAttribute('photo')->getValue();

        $imagePub ='pub/media' . $img;

        $nameCustomer = [];
        $nameCustomer['id']=$customer->getId();
        $nameCustomer['email']=$customer->getEmail();
        $nameCustomer['name']=$customer->getFirstname() . ' ' . $customer->getLastname();
        $nameCustomer['ci']=$customer->getCustomAttribute('ci')->getValue();

        $name=(string)json_encode($nameCustomer);
        $registry = $this->luxand->createCustomer($name, 0, $imagePub);

        if ($registry) {
            $faceId = $this->luxand->getCustomerFace($registry);
            $customer->setCustomAttribute('luxand_registry', 1);
            $customer->setCustomAttribute('luxand_id', $registry);
            $customer->setCustomAttribute('luxand_photo_id', $faceId);
            $this->customerRepository->save($customer);
        }

        return $this;
    }
}
