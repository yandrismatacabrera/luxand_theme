<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05/06/19
 * Time: 04:49 PM
 */
namespace OY\GymBooking\Model\Repository;

use Magento\Framework\Exception\AbstractAggregateException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use OY\GymBooking\Api\Data;
use OY\GymBooking\Api\GymClassDateRepositoryInterface;
use OY\GymBooking\Model\ResourceModel\GymClassDate as GymClassDate;

class GymClassDateRepository implements GymClassDateRepositoryInterface
{
    protected $resourceGymClassDate;

    protected $gymClassDateFactory;

    protected $gymClassDateCollectionFactory;

    public function __construct(
        GymClassDate $resource,
        \OY\GymBooking\Model\GymClassDateFactory $gymClassDateFactory,
        \OY\GymBooking\Model\ResourceModel\GymClassDate\CollectionFactory $gymClassDateCollectionFactory
    ) {
        $this->resourceGymClassDate = $resource;
        $this->gymClassDateFactory = $gymClassDateFactory;
        $this->gymClassDateCollectionFactory = $gymClassDateCollectionFactory;
    }

    public function save(\OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate)
    {
        try {
            $this->resourceGymClassDate->save($gymClassDate);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $gymClassDate;
    }

    public function getById($gymClassDateId)
    {
        $model = $this->gymClassDateFactory->create();
        $this->resourceGymClassDate->load($model, $gymClassDateId);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Info class with id "%1" does not exist.', $gymClassDateId));
        }
        return $model;
    }

    public function delete(Data\GymClassDateInterface $model)
    {
        try {
            $this->resourceGymClassDate->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($gymClassDateId)
    {
        return $this->delete($this->getById($gymClassDateId));
    }

    public function getAll()
    {
        return $this->gymClassDateCollectionFactory->create();
    }
}
