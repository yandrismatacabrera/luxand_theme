<?php
namespace OY\Routine\Model\ResourceModel\Exercise;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('OY\Routine\Model\Exercise', 'OY\Routine\Model\ResourceModel\Exercise');
    }
}