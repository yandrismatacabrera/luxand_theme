<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21/06/21
 * Time: 09:20 PM
 */

namespace Gym\Gallery\Helper;


class GooglePlace extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct($context);
        $this->directoryList = $directoryList;
    }

    private function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAPIKey(){
        return $this->getConfig("luxand_gallery/config_gallery/api_key");
    }

    public function getPlaceId(){
        return $this->getConfig("luxand_gallery/config_gallery/place_id");
    }

    public function getPlaceDetails() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/details/json?place_id=".$this->getPlaceId()."&fields=photo&key=".$this->getAPIKey(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => [ ]
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            error_log("cURL Error #:" . $err);
        } else {
            return $response;
        }
    }

    public function getPlacePhoto($photoRef) {
        return "https://maps.googleapis.com/maps/api/place/photo?maxwidth=1200&photoreference=".$photoRef."&key=".$this->getAPIKey();
    }

    public function getImagesPath() {
        $mediaDir = $this->directoryList->getPath('media');
        $images = glob($mediaDir.'/gallery/'.'*');
        $result = [];
        foreach($images as $image) {
            $array = explode("/", $image);
            $result[] =  $array[count($array) - 1];
        }
        return $result;
    }
}