<?php
namespace OY\GymBooking\Api;

interface GymClassBookingRepositoryInterface
{
    /**
     * @param \OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking
     * @return \OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking);

    /**
     * @param int $gymClassBookingId
     * @return \OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking
     * @throws NoSuchEntityException
     */
    public function getById($gymClassBookingId);

    /**
     * @param \OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking
     * @return bool
     */
    public function delete(\OY\GymBooking\Api\Data\GymClassBookingInterface $gymClassBooking);

    /**
     * @param int $gymClassBookingId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @return bool
     */
    public function deleteById($gymClassBookingId);

    /**
     * @return \OY\GymBooking\Model\ResourceModel\GymClassBooking\Collection
     * @throws NoSuchEntityException
     */
    public function getAll();
}
