<?php
declare(strict_types=1);

namespace Kadoco\CancelOrder\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class CancelOrderInformationProvider implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    const ORDER_STATUS_CAN_CANCEL = 'pending';
    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    public function __construct(
        RequestInterface $request,
        OrderRepositoryInterface $orderRepository,
        UrlInterface $url
    ) {
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        $this->url = $url;
    }

    public function canCancelOrder():bool
    {
        $orderId = $this->getOrderId();
        if (!$orderId) {
            return false;
        }
        $order = $this->orderRepository->get($orderId);

        return $order->getStatus() === self::ORDER_STATUS_CAN_CANCEL;
    }

    public function getOrderId():?int
    {
        $id = (int) $this->request->getParam('order_id');
        if (!$id) {
            return null;
        }

        return $id;
    }

    public function getCancelUrl():string
    {
        $orderId = $this->getOrderId();

        return $this->url->getUrl('cancelorder/index/index', ['order_id' => $orderId]);
    }
}
