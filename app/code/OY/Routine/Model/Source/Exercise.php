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
 * @property \OY\Routine\Model\ExerciseFactory exerciseFactory
 */
class Exercise extends AbstractSource
{
    public function __construct(
        \OY\Routine\Model\ExerciseFactory $exerciseFactory
    )
    {
        $this->exerciseFactory = $exerciseFactory;
    }


    public function getAllOptions() {
        $exerciseObj = $this->exerciseFactory->create();
        $collection = $exerciseObj->getCollection();
        $exercises = [];

        $exercises[] = [
            'label' => ' ',
            'value' => ''
        ];

        foreach ($collection as $exercise) {
            $exercises[] = [
                'label' => $exercise->getName(),
                'value' => $exercise->getExerciseId()
            ];
        }

        return $exercises;
    }
}