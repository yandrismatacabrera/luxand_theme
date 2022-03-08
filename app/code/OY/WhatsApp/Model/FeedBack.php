<?php
namespace OY\WhatsApp\Model;

use OY\WhatsApp\Api\FeedBackInterface;
use Magento\Framework\Webapi\Rest\Request as Request;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;

class FeedBack implements FeedBackInterface
{
    private $request;

    public function __construct(
        \Magento\Framework\Webapi\Rest\Request $request,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->request=$request;
        $this->logger=$logger;
    }


    public function receptor()
    {
        $params = $this->request->getBodyParams();

        $this->logger->info('WhastApp ',$params);
        print_r($params);die;
    }

}
