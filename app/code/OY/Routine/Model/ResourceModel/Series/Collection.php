<?php
namespace OY\Routine\Model\ResourceModel\Series;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('OY\Routine\Model\Series', 'OY\Routine\Model\ResourceModel\Series');
    }
}