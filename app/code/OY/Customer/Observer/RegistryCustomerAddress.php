<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04/12/20
 * Time: 12:14 AM
 */
namespace OY\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Lentesplus\Stores\Api\Data\StoresInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @property \Magento\Customer\Api\CustomerRepositoryInterface customerRepository
 * @property \Magento\Customer\Model\AddressFactory addressFactory
 */
class RegistryCustomerAddress implements ObserverInterface
{
    public function __construct (
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->addressDataFactory = $addressDataFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $customer = $this->customerRepository->getById($observer->getCustomer()->getId());
            if (!$customer->getDefaultBilling()) {
                $city = '';
                if ($customer->getCustomAttribute('address_city') && $customer->getCustomAttribute('address_city')->getValue()) {
                    $city = $customer->getCustomAttribute('address_city')->getValue();
                }

                $postcode = '';
                if ($customer->getCustomAttribute('address_postal_code') && $customer->getCustomAttribute('address_postal_code')->getValue()) {
                    $postcode = $customer->getCustomAttribute('address_postal_code')->getValue();
                }

                $street1 = '';
                if ($customer->getCustomAttribute('address_street_1') && $customer->getCustomAttribute('address_street_1')->getValue()) {
                    $street1 = $customer->getCustomAttribute('address_street_1')->getValue();
                }

                $street2 = '';
                if ($customer->getCustomAttribute('address_street_2') && $customer->getCustomAttribute('address_street_2')->getValue()) {
                    $street2 = $customer->getCustomAttribute('address_street_2')->getValue();
                }

                $address = $this->addressDataFactory->create();
                $address->setFirstname($customer->getFirstname())
                    ->setLastname($customer->getLastname())
                    ->setCountryId('UY')
                    ->setCity($city)
                    ->setPostcode($postcode)
                    ->setCustomerId($customer->getId())
                    ->setStreet([$street1, $street2])
                    ->setIsDefaultBilling('1');

                $this->addressRepository->save($address);
            }
        } catch (\Exception $ex) {

        }
        return $this;
    }
}
