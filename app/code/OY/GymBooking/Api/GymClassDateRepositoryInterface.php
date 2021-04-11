<?php
namespace OY\GymBooking\Api;

interface GymClassDateRepositoryInterface
{
    /**
     * @param \OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate
     * @return \OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate);

    /**
     * @param int $gymClassDateId
     * @return \OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate
     * @throws NoSuchEntityException
     */
    public function getById($gymClassDateId);

    /**
     * @param \OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate
     * @return bool
     */
    public function delete(\OY\GymBooking\Api\Data\GymClassDateInterface $gymClassDate);

    /**
     * @param int $gymClassDateId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @return bool
     */
    public function deleteById($gymClassDateId);

    /**
     * @return \OY\GymBooking\Model\ResourceModel\GymClassDate\Collection
     * @throws NoSuchEntityException
     */
    public function getAll();
}
