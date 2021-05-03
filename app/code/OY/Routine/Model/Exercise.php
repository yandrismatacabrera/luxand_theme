<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/04/21
 * Time: 11:56 PM
 */

namespace OY\Routine\Model;


use OY\Routine\Api\Data\ExerciseInterface;

class Exercise extends \Magento\Framework\Model\AbstractModel implements ExerciseInterface
{
    const CACHE_TAG = 'oy_routine_exercise';

    protected $_cacheTag = 'oy_routine_exercise';

    protected $_eventPrefix = 'oy_routine_exercise';

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
        $this->_init('OY\Routine\Model\ResourceModel\Exercise');
    }

    /**
     * @return int
     */
    public function getExerciseId() {
        return $this->getData(self::EXERCISE_ID);
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
    public function getImage() {
        return $this->getData(self::IMAGE);
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
    public function setExerciseId($id) {
        return $this->setData(self::EXERCISE_ID, $id);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image) {
        return $this->setData(self::IMAGE, $image);
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