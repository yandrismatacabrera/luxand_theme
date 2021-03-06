<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/04/21
 * Time: 09:48 PM
 */

namespace OY\Routine\Controller\Adminhtml\Exercise;


use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \OY\Routine\Model\ExerciseFactory
     */
    var $exerciseFactory;

    protected $fileSystem;
    protected $uploaderFactory;
    protected $adapterFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \OY\Routine\Model\ExerciseFactory $exerciseFactory
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Image\AdapterFactory $adapterFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \OY\Routine\Model\ExerciseFactory $exerciseFactory,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory
    ) {
        parent::__construct($context);
        $this->exerciseFactory = $exerciseFactory;
        $this->fileSystem = $fileSystem;
        $this->adapterFactory = $adapterFactory;
        $this->uploaderFactory = $uploaderFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('oy_routine/exercise/addexercise');
            return;
        }
        try {
            if ((isset($_FILES['image']['name'])) && ($_FILES['image']['name'] != '') && (!isset($data['image']['delete'])))
            {
                try
                {
                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image']);
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploaderFactory->setAllowRenameFiles(true);
                    $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('OY_Routine_IMG');
                    $result = $uploaderFactory->save($destinationPath);

                    if (!$result)
                    {
                        throw new LocalizedException
                        (
                            __('File cannot be saved to path: $1', $destinationPath)
                        );
                    }

                    $imagePath = 'OY_Routine_IMG' . $result['file'];

                    $data['image'] = $imagePath;

                }
                catch (\Exception $e)
                {
                    $this->messageManager->addError(__("Image not Upload Please Try Again"));
                }
            } else if ((isset($data['image']['value'])) && ($data['image']['value'] != '')) {
                $data['image'] = $data['image']['value'];
            }

            //Image One
            if ((isset($_FILES['image_one']['name'])) && ($_FILES['image_one']['name'] != '') && (!isset($data['image_one']['delete'])))
            {
                try
                {
                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image_one']);
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploaderFactory->setAllowRenameFiles(true);
                    $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('OY_Routine_IMG');
                    $result = $uploaderFactory->save($destinationPath);

                    if (!$result)
                    {
                        throw new LocalizedException
                        (
                            __('File cannot be saved to path: $1', $destinationPath)
                        );
                    }

                    $imagePath = 'OY_Routine_IMG' . $result['file'];

                    $data['image_one'] = $imagePath;

                }
                catch (\Exception $e)
                {
                    $this->messageManager->addError(__("Image not Upload Please Try Again"));
                }
            } else if ((isset($data['image_one']['value'])) && ($data['image_one']['value'] != '')) {
                $data['image_one'] = $data['image_one']['value'];
            }

            //Image Two
            if ((isset($_FILES['image_two']['name'])) && ($_FILES['image_two']['name'] != '') && (!isset($data['image_two']['delete'])))
            {
                try
                {
                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image_two']);
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploaderFactory->setAllowRenameFiles(true);
                    $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('OY_Routine_IMG');
                    $result = $uploaderFactory->save($destinationPath);

                    if (!$result)
                    {
                        throw new LocalizedException
                        (
                            __('File cannot be saved to path: $1', $destinationPath)
                        );
                    }

                    $imagePath = 'OY_Routine_IMG' . $result['file'];

                    $data['image_two'] = $imagePath;

                }
                catch (\Exception $e)
                {
                    $this->messageManager->addError(__("Image not Upload Please Try Again"));
                }
            } else if ((isset($data['image_two']['value'])) && ($data['image_two']['value'] != '')) {
                $data['image_two'] = $data['image_two']['value'];
            }

            //Image Three
            if ((isset($_FILES['image_three']['name'])) && ($_FILES['image_three']['name'] != '') && (!isset($data['image_three']['delete'])))
            {
                try
                {
                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image_three']);
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploaderFactory->setAllowRenameFiles(true);
                    $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('OY_Routine_IMG');
                    $result = $uploaderFactory->save($destinationPath);

                    if (!$result)
                    {
                        throw new LocalizedException
                        (
                            __('File cannot be saved to path: $1', $destinationPath)
                        );
                    }

                    $imagePath = 'OY_Routine_IMG' . $result['file'];

                    $data['image_three'] = $imagePath;

                }
                catch (\Exception $e)
                {
                    $this->messageManager->addError(__("Image not Upload Please Try Again"));
                }
            } else if ((isset($data['image_three']['value'])) && ($data['image_three']['value'] != '')) {
                $data['image_three'] = $data['image_three']['value'];
            }


            $rowData = $this->exerciseFactory->create();
            $rowData->setData($data);
            if (isset($data['exercise_id'])) {
                $rowData->setExerciseId($data['exercise_id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('El Ejercicio fue guardado exitosamente.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('oy_routine/exercise/index');
    }
}