<?php
namespace OY\Catalog\Model\Config\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Classesoption extends AbstractSource
{
    public function __construct(
        \OY\GymBooking\Model\ResourceModel\GymClass\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory=$collectionFactory;
    }

    public function getAllOptions()
    {
        $collection = $this->collectionFactory->create();

        $this->_options = [];
        $this->_options[] = ['label' => ' ', 'value' => ''];
        if ($collection->getSize()) {
            foreach ($collection as $item) {
                $this->_options[] = ['label' => $item->getData('name'), 'value' => $item->getData('entity_id')];
            }
        }

        return $this->_options;
    }
}
