<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/05/21
 * Time: 04:52 PM
 */

namespace OY\Routine\Api;

use OY\Routine\Api\Data\SeriesInterface;

interface SeriesRepositoryInterface
{
    /**
     * @param SeriesInterface $series
     * @return SeriesInterface $series
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(SeriesInterface $series);

    /**
     * @param int $seriesId
     * @return SeriesInterface $series
     * @throws NoSuchEntityException
     */
    public function getById($seriesId);

    /**
     * @param SeriesInterface $series
     * @return bool
     */
    public function delete(SeriesInterface $series);

    /**
     * @param int $seriesId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @return bool
     */
    public function deleteById($seriesId);

    /**
     * @return \OY\Routine\Model\ResourceModel\Series\Collection
     * @throws NoSuchEntityException
     */
    public function getAll();
}