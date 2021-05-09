<?php
namespace OY\Routine\Model\ResourceModel\Routine;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('OY\Routine\Model\Routine', 'OY\Routine\Model\ResourceModel\Routine');
    }
}