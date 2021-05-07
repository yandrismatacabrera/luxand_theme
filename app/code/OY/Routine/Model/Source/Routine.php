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
class Routine extends AbstractSource
{
    public function __construct(
        \OY\Routine\Model\RoutineFactory $routineFactory
    )
    {
        $this->routineFactory = $routineFactory;
    }


    public function getAllOptions() {
        $routineObj = $this->routineFactory->create();
        $collection = $routineObj->getCollection();
        $routines = [];

        $routines[] = [
            'label' => ' ',
            'value' => ''
        ];

        foreach ($collection as $routine) {
            $routines[] = [
                'label' => $routine->getName(),
                'value' => $routine->getRoutineId()
            ];
        }

        return $routines;
    }
}