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
use OY\Routine\Api\Data\SeriesInterface;
use OY\Routine\Api\SeriesRepositoryInterface;

class SeriesRepository implements SeriesRepositoryInterface
{
    protected $resourceSeries;

    protected $seriesFactory;

    protected $seriesCollectionFactory;

    public function __construct(
        \OY\Routine\Model\ResourceModel\Series $resource,
        \OY\Routine\Model\SeriesFactory $seriesFactory,
        \OY\Routine\Model\ResourceModel\Series\CollectionFactory $seriesCollectionFactory
    ) {
        $this->resourceSeries = $resource;
        $this->seriesFactory = $seriesFactory;
        $this->seriesCollectionFactory = $seriesCollectionFactory;
    }


    /**
     * @param SeriesInterface $series
     * @return SeriesInterface $series
     * @throws CouldNotSaveException
     */
    public function save(SeriesInterface $series)
    {
        try {
            $this->resourceSeries->save($series);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $series;
    }

    /**
     * @param int $seriesId
     * @return SeriesInterface $series
     * @throws NoSuchEntityException
     */
    public function getById($seriesId)
    {
        $model = $this->seriesFactory->create();
        $this->resourceSeries->load($model, $seriesId);
        if (!$model->getSeriesId()) {
            throw new NoSuchEntityException(__('Info class with id "%1" does not exist.', $seriesId));
        }
        return $model;
    }

    /**
     * @param SeriesInterface $series
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SeriesInterface $series)
    {
        try {
            $this->resourceSeries->delete($series);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param int $seriesId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($seriesId)
    {
        return $this->delete($this->getById($seriesId));
    }

    /**
     * @return \OY\Routine\Model\ResourceModel\Series\Collection
     */
    public function getAll()
    {
        return $this->seriesCollectionFactory->create();
    }
}