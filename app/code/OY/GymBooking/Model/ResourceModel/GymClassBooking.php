<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05/06/19
 * Time: 05:33 PM
 */
namespace OY\GymBooking\Model\ResourceModel;

class GymClassBooking extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    protected function _construct()
    {
        $this->_init('gym_booking', 'entity_id');
    }

    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'entity_id';
        }

        return parent::load($object, $value, $field);
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {

            $select->limit(
                1
            );
        }

        return $select;
    }
}
