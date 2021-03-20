<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18/03/21
 * Time: 09:13 PM
 */

namespace OY\Customer\Plugin;


/**
 * @property \OY\Registry\Helper\Luxand luxand
 */
class DeleteCustomer
{
    public function __construct(
        \OY\Registry\Helper\Luxand $luxand,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->luxand = $luxand;
        $this->customerRepository=$customerRepository;
    }

    public function aroundDeleteById(
        \Magento\Customer\Model\ResourceModel\CustomerRepository $subject,
        \Closure $proceed,
        $customerId
    ) {
        $customer = $this->customerRepository->getById($customerId);
        if ($customer &&
            $customer->getCustomAttribute('luxand_id') &&
            $customer->getCustomAttribute('luxand_id')->getValue()) {
            $registry = $this->luxand->deleteCustomer($customer->getCustomAttribute('luxand_id')->getValue());
        }

        // It will proceed ahead with the default delete function
        $result = $proceed($customerId);

        return $result;
    }
}