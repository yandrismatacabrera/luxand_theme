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
use OY\GymBooking\Api\GymClassBookingRepositoryInterface;
use OY\GymBooking\Model\ResourceModel\GymClassBooking as GymClassBooking;

class GymClassBookingRepository implements GymClassBookingRepositoryInterface
{
    protected $resourceGymClassBooking;

    protected $gymClassBookingFactory;

    protected $gymClassBookingCollectionFactory;

    public function __construct(
        GymClassBooking $resource,
        \OY\GymBooking\Model\GymClassBookingFactory $gymClassBookingFactory,
        \OY\GymBooking\Model\ResourceModel\GymClassBooking\CollectionFactory $gymClassBookingCollectionFactory
    ) {
        $this->resourceGymClassBooking = $resource;
        $this->gymClassBookingFactory = $gymClassBookingFactory;
        $this->gymClassBookingCollectionFactory = $gymClassBookingCollectionFactory;
    }

    public function save(\OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking)
    {
        try {
            $this->resourceGymClassBooking->save($gymClassBooking);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $gymClassBooking;
    }

    public function getById($gymClassBookingId)
    {
        $model = $this->gymClassBookingFactory->create();
        $this->resourceGymClassBooking->load($model, $gymClassBookingId);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Info class with id "%1" does not exist.', $gymClassBookingId));
        }
        return $model;
    }

    public function delete(Data\GymClassBookingInterface $model)
    {
        try {
            $this->resourceGymClassBooking->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($gymClassBookingId)
    {
        return $this->delete($this->getById($gymClassBookingId));
    }

    public function getAll()
    {
        return $this->gymClassBookingCollectionFactory->create();
    }
}
