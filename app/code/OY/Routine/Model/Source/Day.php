<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/03/21
 * Time: 08:07 PM
 */

namespace OY\Routine\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * @property \OY\Routine\Model\RoutineFactory routineFactory
 */
class Day extends AbstractSource
{
    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function getOptionArray()
    {
        $options = ['0' => __('Domingo'),'1' => __('Lunes'),'2' => __('Martes'),'3' => __('Miércoles'),'4' => __('Jueves'),'5' => __('Viernes'),'6' => __('Sábado')];
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