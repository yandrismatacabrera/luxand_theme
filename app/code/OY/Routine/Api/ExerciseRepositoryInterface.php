<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08/05/21
 * Time: 05:30 PM
 */

namespace OY\Routine\Api;


use OY\Routine\Api\Data\ExerciseInterface;

interface ExerciseRepositoryInterface
{
    /**
     * @param ExerciseInterface $exercise
     * @return ExerciseInterface $exercise
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ExerciseInterface $exercise);

    /**
     * @param int $exerciseId
     * @return ExerciseInterface $exercise
     * @throws NoSuchEntityException
     */
    public function getById($exerciseId);

    /**
     * @param ExerciseInterface $exercise
     * @return bool
     */
    public function delete(ExerciseInterface $exercise);

    /**
     * @param int $exerciseId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @return bool
     */
    public function deleteById($exerciseId);

    /**
     * @return \OY\Routine\Model\ResourceModel\Exercise\Collection
     * @throws NoSuchEntityException
     */
    public function getAll();
}