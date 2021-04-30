<?php
namespace OY\GymBooking\Ui\DataProvider;

class ClassesDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;

    protected $_coreRegistry;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\Registry $registry,
        \OY\GymBooking\Model\ResourceModel\GymClass\CollectionFactory $classCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->_coreRegistry = $registry;
        $this->classCollectionFactory=$classCollectionFactory;
        $this->customerSession=$customerSession;
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $this->_loadedData = [];

        $model = $this->customerSession->getGymclass();

        if ($model && $model->getId()) {
            $data = $model->getData();
            if ($data['required_booking']) {
                $data['required_booking']=true;
            }
            $imageName = explode('/', $data['image']);
            $dataImage = [
                [
                    'name' => $imageName[count($imageName)-1],
                    'file' => $imageName[count($imageName)-1],
                    'type' => 'image',
                    'url' => $data['image'],
                ],
            ];
            $data['image']=$dataImage;

            $this->_loadedData[$model->getId()]=$data;
        }

        return $this->_loadedData;
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return null;
    }
}
