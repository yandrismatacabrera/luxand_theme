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
        \OY\Routine\Model\ResourceModel\Routine $resource,
        \OY\Routine\Model\RoutineFactory $routineFactory,
        \OY\Routine\Model\ResourceModel\Routine\CollectionFactory $routineCollectionFactory
    ) {
        $this->resourceRoutine = $resource;
        $this->routineFactory = $routineFactory;
        $this->routineCollectionFactory = $routineCollectionFactory;
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
}