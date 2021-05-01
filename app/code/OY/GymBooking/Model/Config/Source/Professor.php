<?php
namespace OY\GymBooking\Model\Config\Source;

class Professor implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionFactory
    ) {
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
        $collection->addAttributeToFilter('prof_entity_id', ['neq' => '']);

        $list = [['value' => '', 'label' => ' ']];
        foreach ($collection as $item) {
            $list[]=['value' => $item->getId(), 'label' => $item->getFirstname().' '.$item->getLastname()];
        }
        return $list;
    }
}
