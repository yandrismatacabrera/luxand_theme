<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05/05/21
 * Time: 10:34 PM
 */

namespace OY\Routine\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Complexity implements OptionSourceInterface
{
    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function getOptionArray()
    {
        $options = ['0' => __('Baja'),'1' => __('Media'),'2' => __('Alta')];
        return $options;
    }

    /**
     * Get Grid row status labels array with empty value for option element.
     *
     * @return array
     */
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }

    /**
     * Get Grid row type array for option element.
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}