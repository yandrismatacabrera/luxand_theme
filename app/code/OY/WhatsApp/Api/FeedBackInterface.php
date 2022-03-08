<?php
namespace OY\WhatsApp\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface FeedBackInterface
{
    /**
     * POST for Notification api
     * @return mixed
     */
    public function receptor();
}
