<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08/05/21
 * Time: 05:29 PM
 */

namespace OY\Routine\Model\Repository;


use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use OY\Routine\Api\Data\ExerciseInterface;
use OY\Routine\Api\ExerciseRepositoryInterface;

class ExerciseRepository implements ExerciseRepositoryInterface
{
    protected $resourceExercise;

    protected $exerciseFactory;

    protected $exerciseCollectionFactory;

    public function __construct(
        \OY\Routine\Model\ResourceModel\Exercise $resource,
        \OY\Routine\Model\ExerciseFactory $exerciseFactory,
        \OY\Routine\Model\ResourceModel\Exercise\CollectionFactory $exerciseCollectionFactory
    ) {
        $this->resourceExercise = $resource;
        $this->exerciseFactory = $exerciseFactory;
        $this->exerciseCollectionFactory = $exerciseCollectionFactory;
    }


    /**
     * @param ExerciseInterface $exercise
     * @return ExerciseInterface $exercise
     * @throws CouldNotSaveException
     */
    public function save(ExerciseInterface $exercise)
    {
        try {
            $this->resourceExercise->save($exercise);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $exercise;
    }

    /**
     * @param int $exerciseId
     * @return ExerciseInterface $exercise
     * @throws NoSuchEntityException
     */
    public function getById($exerciseId)
    {
        $model = $this->exerciseFactory->create();
        $this->resourceExercise->load($model, $exerciseId);
        if (!$model->getExerciseId()) {
            throw new NoSuchEntityException(__('Info class with id "%1" does not exist.', $exerciseId));
        }
        return $model;
    }

    /**
     * @param ExerciseInterface $exercise
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ExerciseInterface $exercise)
    {
        try {
            $this->resourceExercise->delete($exercise);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param int $exerciseId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($exerciseId)
    {
        return $this->delete($this->getById($exerciseId));
    }

    /**
     * @return \OY\Routine\Model\ResourceModel\Exercise\Collection
     */
    public function getAll()
    {
        return $this->exerciseCollectionFactory->create();
    }
}