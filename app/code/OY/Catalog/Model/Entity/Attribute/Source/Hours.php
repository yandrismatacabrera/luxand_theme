<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21/12/20
 * Time: 07:24 PM
 */
namespace OY\Catalog\Model\Entity\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Hours extends AbstractSource
{
    public function getAllOptions()
    {
        $options = [];
        $options[]=['label'=>' ','value'=>' '];
        for ($h=0; $h<24; $h++){
            $opt = [];
            if($h<10){
                $timeH = '0'.$h;
            }else{
                $timeH = $h;
            }
            for ($m =0; $m<=55; $m+=5){
                if($m<10){
                    $timeM = '0'.$m;
                }else{
                    $timeM = $m;
                }
                $opt['label']=$timeH.':'.$timeM;
                $opt['value']=$timeH.':'.$timeM;
                $options[]=$opt;
            }

        }
        return $options;
    }
}
