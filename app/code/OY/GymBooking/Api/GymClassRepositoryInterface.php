<?php
namespace OY\GymBooking\Api;

interface GymClassRepositoryInterface
{
    /**
     * @param \OY\GymBooking\Api\Data\GymClassInterface $gymClass
     * @return \OY\GymBooking\Api\Data\GymClassInterface $gymClass
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\OY\GymBooking\Api\Data\GymClassInterface $gymClass);

    /**
     * @param int $gymClassId
     * @return \OY\GymBooking\Api\Data\GymClassInterface $gymClass
     * @throws NoSuchEntityException
     */
    public function getById($gymClassId);

    /**
     * @param \OY\GymBooking\Api\Data\GymClassInterface $gymClass
     * @return bool
     */
    public function delete(\OY\GymBooking\Api\Data\GymClassInterface $gymClass);

    /**
     * @param int $gymClassDateId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @return bool
     */
    public function deleteById($gymClassDateId);

    /**
     * @return \OY\GymBooking\Model\ResourceModel\GymClass\Collection
     * @throws NoSuchEntityException
     */
    public function getAll();
}
