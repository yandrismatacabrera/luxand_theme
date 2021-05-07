<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04/05/21
 * Time: 08:19 PM
 */

namespace OY\Routine\Model\ResourceModel;


class Routine extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('routine_entity', 'routine_id');
    }
}