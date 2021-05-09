<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OY\Customer\Block\Adminhtml\Series\Grid\Renderer;

use Magento\Store\Model\StoreManagerInterface;

class SeriesImage extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var Action\UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param Action\UrlBuilder $actionUrlBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \OY\Routine\Model\ExerciseFactory $exerciseFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->exerciseFactory = $exerciseFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Render action
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $collection = $this->exerciseFactory->create();
        $exercise = $collection->load($row->getData('exercise_id'));
        $url = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $exercise->getImage();

        return '<img class="admin__control-thumbnail" width="150" src="'. $url .'" alt="">';
    }
}
