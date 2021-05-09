<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OY\Customer\Block\Adminhtml\Series\Grid\Renderer;

class SeriesName extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
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
        array $data = []
    ) {
        $this->exerciseFactory = $exerciseFactory;
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

        return $exercise->getName();
    }
}
