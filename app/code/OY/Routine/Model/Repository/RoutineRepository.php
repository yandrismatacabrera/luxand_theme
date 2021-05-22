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
use OY\Routine\Api\Data\RoutineInterface;
use OY\Routine\Api\RoutineRepositoryInterface;

class RoutineRepository implements RoutineRepositoryInterface
{
    protected $resourceRoutine;

    protected $routineFactory;

    protected $routineCollectionFactory;

    public function __construct(
        \OY\Routine\Model\ResourceModel\Routine $resourceRoutine,
        \OY\Routine\Model\ResourceModel\Exercise $resourceExercise,
        \OY\Routine\Model\RoutineFactory $routineFactory,
        \OY\Routine\Model\ExerciseFactory $exerciseFactory,
        \OY\Routine\Model\ResourceModel\Routine\CollectionFactory $routineCollectionFactory,
        \OY\Routine\Model\ResourceModel\Series\CollectionFactory $seriesCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        $this->resourceRoutine = $resourceRoutine;
        $this->resourceExercise = $resourceExercise;
        $this->routineFactory = $routineFactory;
        $this->exerciseFactory = $exerciseFactory;
        $this->routineCollectionFactory = $routineCollectionFactory;
        $this->seriesCollectionFactory = $seriesCollectionFactory;
        $this->customerRepository = $customerRepository;
    }


    /**
     * @param RoutineInterface $routine
     * @return RoutineInterface $routine
     * @throws CouldNotSaveException
     */
    public function save(RoutineInterface $routine)
    {
        try {
            $this->resourceRoutine->save($routine);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $routine;
    }

    /**
     * @param int $routineId
     * @return RoutineInterface $routine
     * @throws NoSuchEntityException
     */
    public function getById($routineId)
    {
        $model = $this->routineFactory->create();
        $this->resourceRoutine->load($model, $routineId);
        if (!$model->getRoutineId()) {
            throw new NoSuchEntityException(__('Info class with id "%1" does not exist.', $routineId));
        }
        return $model;
    }

    /**
     * @param RoutineInterface $routine
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(RoutineInterface $routine)
    {
        try {
            $this->resourceRoutine->delete($routine);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param int $routineId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($routineId)
    {
        return $this->delete($this->getById($routineId));
    }

    /**
     * @return \OY\Routine\Model\ResourceModel\Routine\Collection
     */
    public function getAll()
    {
        return $this->routineCollectionFactory->create();
    }

    /**
     * Customer Routine.
     *
     * @api
     * @param int $customerId
     * @return  mixed[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRoutine($customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        $routineId = '';
        if ($customer->getCustomAttribute('routine_entity_id') && $customer->getCustomAttribute('routine_entity_id')->getValue()) {
            $routineId = $customer->getCustomAttribute('routine_entity_id')->getValue();
        } else {
            return [];
        }

        $data = [];
        $routine = $this->routineFactory->create();
        $this->resourceRoutine->load($routine, $routineId);
        $data = [
            'routine_id' => $routine->getRoutineId(),
            'name' => $routine->getName(),
            'complexity' => $routine->getComplexity(),
            'duration' => $routine->getDuration(),
        ];
        $collection = $this->seriesCollectionFactory->create();
        $collection->addFieldToFilter('routine_id', $routineId);
        foreach ($collection as $series) {
            $exercise = $this->exerciseFactory->create();
            $this->resourceExercise->load($exercise, $series->getExerciseId());
            $exerciseData = [
                'exercise_id' => $exercise->getExerciseId(),
                'name' => $exercise->getName(),
                'image' => $exercise->getImage(),
                'image_one' => $exercise->getImageOne(),
                'image_two' => $exercise->getImageTwo(),
                'image_three' => $exercise->getImageThree(),
            ];
            $seriesData = [
                'series_id' => $series->getSeriesId(),
                'order' => $series->getOrder(),
                'number_of_series' => $series->getNumberOfSeries(),
                'break_time' => $series->getBreakTime(),
                'number_of_repetitions' => $series->getNumberOfRepetitions(),
                'day' => $series->getDay(),
                'exercise' => $exerciseData,
            ];
            $data['series'][] = $seriesData;
        }
        return [$data];
    }
}