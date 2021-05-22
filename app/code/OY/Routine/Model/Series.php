<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06/05/21
 * Time: 06:03 PM
 */

namespace OY\Routine\Model;

use OY\Routine\Api\Data\SeriesInterface;
use OY\Routine\Api\Data\varchar;

class Series extends \Magento\Framework\Model\AbstractModel implements SeriesInterface
{
    const CACHE_TAG = 'oy_routine_series';

    protected $_cacheTag = 'oy_routine_series';

    protected $_eventPrefix = 'oy_routine_series';

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
        $this->_init('OY\Routine\Model\ResourceModel\Series');
    }

    /**
     * @return int
     */
    public function getSeriesId()
    {
        return $this->getData(self::SERIES_ID);
    }

    /**
     * @return int
     */
    public function getRoutineId()
    {
        return $this->getData(self::ROUTINE_ID);
    }

    /**
     * @return int
     */
    public function getExerciseId()
    {
        return $this->getData(self::EXERCISE_ID);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->getData(self::ORDER);
    }

    /**
     * @return int
     */
    public function getNumberOfSeries()
    {
        return $this->getData(self::NUMBER_OF_SERIES);
    }

    /**
     * @return int
     */
    public function getBreakTime()
    {
        return $this->getData(self::BREAK_TIME);
    }

    /**
     * @return int
     */
    public function getNumberOfRepetitions()
    {
        return $this->getData(self::NUMBER_OF_REPETITIONS);
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->getData(self::DAY);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get UpdateAt.
     *
     * @return varchar
     */
    public function getUpdateAt()
    {
        return $this->getData(self::UPDATE_AT);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setSeriesId($id)
    {
        return $this->setData(self::SERIES_ID, $id);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setRoutineId($id)
    {
        return $this->setData(self::ROUTINE_ID, $id);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setExerciseId($id)
    {
        return $this->setData(self::EXERCISE_ID, $id);
    }

    /**
     * @param int $order
     * @return $this
     */
    public function setOrder($order)
    {
        return $this->setData(self::ORDER, $order);
    }

    /**
     * @param int $numberOfSeries
     * @return $this
     */
    public function setNumberOfSeries($numberOfSeries)
    {
        return $this->setData(self::NUMBER_OF_SERIES, $numberOfSeries);
    }

    /**
     * @param int $breakTime
     * @return $this
     */
    public function setBreakTime($breakTime)
    {
        return $this->setData(self::BREAK_TIME, $breakTime);
    }

    /**
     * @param int $numberOfRepetitions
     * @return $this
     */
    public function setNumberOfRepetitions($numberOfRepetitions)
    {
        return $this->setData(self::NUMBER_OF_REPETITIONS, $numberOfRepetitions);
    }

    /**
     * @param int $day
     * @return $this
     */
    public function setDay($day)
    {
        return $this->setData(self::DAY, $day);
    }

    /**
     * Set CreatedAt.
     * @param $createdAt
     * @return
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set UpdateAt.
     * @param $updateAt
     * @return
     */
    public function setUpdateAt($updateAt)
    {
        return $this->setData(self::UPDATE_AT, $updateAt);
    }
}