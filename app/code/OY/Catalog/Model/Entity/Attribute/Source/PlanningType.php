<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21/12/20
 * Time: 07:24 PM
 */
namespace OY\Catalog\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class PlanningType extends AbstractSource
{
    public function getAllOptions()
    {
        return [
            '0' => [
                'label' => 'Semanal',
                'value' => 'Semanal'
            ],
            '1' => [
                'label' => 'Mensual',
                'value' => 'Mensual'
            ],
            '2' => [
                'label' => 'Trimestral',
                'value' => 'Trimestral'
            ],
            '3' => [
                'label' => 'Semestral',
                'value' => 'Semestral'
            ],
            '' => [
                'label' => 'Anual',
                'value' => 'Anual'
            ]
        ];
    }
}
