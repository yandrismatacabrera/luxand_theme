<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27/04/21
 * Time: 10:29 PM
 */

namespace OY\Routine\Api\Data;


interface ExerciseInterface
{
    const EXERCISE_ID = 'exercise_id';
    const NAME = 'name';
    const IMAGE = 'image';
    const IMAGE_ONE = 'image_one';
    const IMAGE_TWO = 'image_two';
    const IMAGE_THREE = 'image_three';
    const UPDATE_AT = 'updated_at';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getExerciseId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getImageOne();

    /**
     * @return string
     */
    public function getImageTwo();

    /**
     * @return string
     */
    public function getImageThree();

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
    public function setExerciseId($id);

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * @param string $image
     * @return $this
     */
    public function setImageOne($image);

    /**
     * @param string $image
     * @return $this
     */
    public function setImageTwo($image);

    /**
     * @param string $image
     * @return $this
     */
    public function setImageThree($image);

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