<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/03/21
 * Time: 08:07 PM
 */

namespace OY\Customer\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Professor extends AbstractSource
{
    public function __construct(
        \Magento\User\Model\ResourceModel\User\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }


    public function getAllOptions() {
        $collection = $this->collectionFactory->create();
        $professors = [];

        $professors[] = [
            'label' => ' ',
            'value' => ''
        ];

        foreach ($collection as $user) {
            if ($user->getRole()->getRoleName() == 'Profesor') {
                $professors[] = [
                    'label' => $user->getFirstname().' '.$user->getLastname(),
                    'value' => $user->getId()
                ];
            }
        }

        return $professors;
    }
}