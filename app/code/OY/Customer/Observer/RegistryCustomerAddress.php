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
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \OY\Registry\Helper\Luxand $luxand
    ) {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->addressDataFactory = $addressDataFactory;
        $this->luxandHelper = $luxand;
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

                $postcode = '11300';
                if ($this->luxandHelper->getPostalCode() != '') {
                    $postcode = $this->luxandHelper->getPostalCode();
                }

                $street1 = '';
                if ($customer->getCustomAttribute('address_street_1') && $customer->getCustomAttribute('address_street_1')->getValue()) {
                    $street1 = $customer->getCustomAttribute('address_street_1')->getValue();
                }

                $address = $this->addressDataFactory->create();
                $address->setFirstname($customer->getFirstname())
                    ->setLastname($customer->getLastname())
                    ->setCountryId('UY')
                    ->setCity($city)
                    ->setPostcode($postcode)
                    ->setCustomerId($customer->getId())
                    ->setStreet([$street1])
                    ->setIsDefaultBilling('1');

                $this->addressRepository->save($address);
            }
        } catch (\Exception $ex) {

        }
        return $this;
    }
}
