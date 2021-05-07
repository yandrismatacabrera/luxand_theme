<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06/05/21
 * Time: 06:09 PM
 */

namespace OY\Routine\Model\ResourceModel;


class Series extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('exercise_series_entity', 'series_id');
    }
}