<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/04/21
 * Time: 12:04 AM
 */

namespace OY\Routine\Model\ResourceModel;


class Exercise extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('exercise_entity', 'exercise_id');
    }
}