<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05/06/19
 * Time: 04:49 PM
 */
namespace OY\GymBooking\Model\Repository;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use OY\GymBooking\Api\Data;
use OY\GymBooking\Api\GymClassRepositoryInterface;
use OY\GymBooking\Model\ResourceModel\GymClass as GymClass;
use Magento\Framework\Webapi\Rest\Request as Request;

class GymClassRepository implements GymClassRepositoryInterface
{
    protected $resourceGymClass;

    protected $gymClassFactory;

    protected $gymClassCollectionFactory;

    public function __construct(
        GymClass $resource,
        \OY\GymBooking\Model\GymClassFactory $gymClassFactory,
        \OY\GymBooking\Model\ResourceModel\GymClass\CollectionFactory $gymClassCollectionFactory,
        Request $request,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->resourceGymClass = $resource;
        $this->gymClassFactory = $gymClassFactory;
        $this->gymClassCollectionFactory = $gymClassCollectionFactory;
        $this->request = $request;
        $this->productRepository=$productRepository;
    }

    public function save(\OY\GymBooking\Api\Data\GymClassInterface $gymClass)
    {
        try {
            $this->resourceGymClass->save($gymClass);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $gymClass;
    }

    public function getById($gymClassId)
    {
        $model = $this->gymClassFactory->create();
        $this->resourceGymClass->load($model, $gymClassId);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Info class with id "%1" does not exist.', $gymClassId));
        }
        return $model;
    }

    public function delete(Data\GymClassInterface $model)
    {
        try {
            $this->resourceGymClass->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($gymClassId)
    {
        return $this->delete($this->getById($gymClassId));
    }

    public function getAll()
    {
        return $this->gymClassCollectionFactory->create();
    }

    public function getClassesByPlan()
    {
        $params = $this->request->getParams();
        $result = [];
        if(isset($params['plan_id'])){
            $plan = $this->productRepository->getById((int)$params['plan_id']);
            if($plan->getData('classes_option')){
                $list = explode(',',$plan->getData('classes_option'));
                foreach ($list as $item){
                    $class = $this->getById($item);
                    $result[]=$class->getData();
                }
            }
        }
        return $result;
    }


}
