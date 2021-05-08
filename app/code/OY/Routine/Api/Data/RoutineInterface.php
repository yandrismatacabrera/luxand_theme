<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/05/21
 * Time: 05:38 PM
 */

namespace OY\Routine\Api\Data;


interface RoutineInterface
{
    const ROUTINE_ID = 'routine_id';
    const NAME = 'name';
    const COMPLEXITY = 'complexity';
    const DURATION = 'duration';
    const UPDATE_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getRoutineId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getComplexity();

    /**
     * @return int
     */
    public function getDuration();

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
    public function setRoutineId($id);

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @param string $complexity
     * @return $this
     */
    public function setComplexity($complexity);

    /**
     * @param int $duration
     * @return $this
     */
    public function setDuration($duration);

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