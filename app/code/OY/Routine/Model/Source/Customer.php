<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/03/21
 * Time: 08:07 PM
 */

namespace OY\Routine\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * @property \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory collectionFactory
 */
class Customer extends AbstractSource
{
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }


    public function getAllOptions() {
        $collection = $this->collectionFactory->create();
        $customers = [];

        $customers[] = [
            'label' => ' ',
            'value' => ''
        ];

        foreach ($collection as $customer) {
            $customers[] = [
                'label' => $customer->getFirstname().' '.$customer->getLastname(),
                'value' => $customer->getId()
            ];
        }

        return $customers;
    }
}