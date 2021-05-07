<?php
namespace OY\GymBooking\Model\Config\Source;

class Professor implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \Magento\User\Model\ResourceModel\User\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
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
