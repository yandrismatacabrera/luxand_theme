<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/21
 * Time: 05:38 PM
 */

namespace OY\Routine\Api\Data;


interface SeriesInterface
{
    const SERIES_ID = 'series_id';
    const ROUTINE_ID = 'routine_id';
    const EXERCISE_ID = 'exercise_id';
    const ORDER = 'order';
    const NUMBER_OF_SERIES = 'number_of_series';
    const BREAK_TIME = 'break_time';
    const NUMBER_OF_REPETITIONS = 'number_of_repetitions';
    const DAY = 'day';
    const UPDATE_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getSeriesId();

    /**
     * @return int
     */
    public function getRoutineId();

    /**
     * @return int
     */
    public function getExerciseId();

    /**
     * @return int
     */
    public function getOrder();

    /**
     * @return int
     */
    public function getNumberOfSeries();

    /**
     * @return int
     */
    public function getBreakTime();

    /**
     * @return int
     */
    public function getNumberOfRepetitions();

    /**
     * @return int
     */
    public function getDay();

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt();

    /**
     * Get UpdateAt.
     *
     * @return varchar
     */
    public function getUpdateAt();

    /**
     * @param int $id
     * @return $this
     */
    public function setSeriesId($id);

    /**
     * @param int $id
     * @return $this
     */
    public function setRoutineId($id);

    /**
     * @param int $id
     * @return $this
     */
    public function setExerciseId($id);

    /**
     * @param int $order
     * @return $this
     */
    public function setOrder($order);

    /**
     * @param int $numberOfSeries
     * @return $this
     */
    public function setNumberOfSeries($numberOfSeries);

    /**
     * @param int $breakTime
     * @return $this
     */
    public function setBreakTime($breakTime);

    /**
     * @param int $numberOfRepetitions
     * @return $this
     */
    public function setNumberOfRepetitions($numberOfRepetitions);

    /**
     * @param int $day
     * @return $this
     */
    public function setDay($day);

    /**
     * Set CreatedAt.
     * @param $createdAt
     * @return
     */
    public function setCreatedAt($createdAt);

    /**
     * Set UpdateAt.
     * @param $updateAt
     * @return
     */
    public function setUpdateAt($updateAt);
}