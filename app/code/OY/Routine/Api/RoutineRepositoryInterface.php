<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/05/21
 * Time: 04:40 PM
 */

namespace OY\Routine\Api;

use OY\Routine\Api\Data\RoutineInterface;

interface RoutineRepositoryInterface
{
    /**
     * @param RoutineInterface $routine
     * @return RoutineInterface $routine
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(RoutineInterface $routine);

    /**
     * @param int $routineId
     * @return RoutineInterface $routine
     * @throws NoSuchEntityException
     */
    public function getById($routineId);

    /**
     * @param RoutineInterface $routine
     * @return bool
     */
    public function delete(RoutineInterface $routine);

    /**
     * @param int $routineId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @return bool
     */
    public function deleteById($routineId);

    /**
     * @return \OY\Routine\Model\ResourceModel\Routine\Collection
     * @throws NoSuchEntityException
     */
    public function getAll();

    /**
     * Customer Routine.
     *
     * @api
     * @param int $customerId
     * @return  mixed[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRoutine($customerId);
}