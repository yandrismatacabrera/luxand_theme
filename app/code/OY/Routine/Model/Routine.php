<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/04/21
 * Time: 11:56 PM
 */

namespace OY\Routine\Model;


use OY\Routine\Api\Data\RoutineInterface;

class Routine extends \Magento\Framework\Model\AbstractModel implements RoutineInterface
{
    const CACHE_TAG = 'oy_routine_routine';

    protected $_cacheTag = 'oy_routine_routine';

    protected $_eventPrefix = 'oy_routine_routine';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->_init('OY\Routine\Model\ResourceModel\Routine');
    }

    /**
     * @return int
     */
    public function getRoutineId() {
        return $this->getData(self::ROUTINE_ID);
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->getData(self::NAME);
    }

    /**
     * @return string
     */
    public function getComplexity() {
        return $this->getData(self::COMPLEXITY);
    }

    /**
     * @return int
     */
    public function getDuration() {
        return $this->getData(self::DURATION);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt() {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get UpdateAt.
     *
     * @return varchar
     */
    public function getUpdateAt() {
        return $this->getData(self::UPDATE_AT);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setRoutineId($id) {
        return $this->setData(self::ROUTINE_ID, $id);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @param string $complexity
     * @return $this
     */
    public function setComplexity($complexity) {
        return $this->setData(self::COMPLEXITY, $complexity);
    }

    /**
     * @param int $duration
     * @return $this
     */
    public function setDuration($duration) {
        return $this->setData(self::DURATION, $duration);
    }

    /**
     * Set CreatedAt.
     * @param $createdAt
     * @return
     */
    public function setCreatedAt($createdAt) {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set UpdateAt.
     * @param $updateAt
     * @return
     */
    public function setUpdateAt($updateAt) {
        return $this->setData(self::UPDATE_AT, $updateAt);
    }
}