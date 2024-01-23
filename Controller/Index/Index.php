<?php
declare(strict_types=1);

namespace Kadoco\CancelOrder\Controller\Index;

use Kadoco\Prescription\Model\GetCartItemsByUniqId;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Model\Quote\Item\Repository;
use Magento\Sales\Api\OrderManagementInterface;

class Index implements ActionInterface
{
    private ResultFactory $resultFactory;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var RedirectInterface
     */
    private RedirectInterface $redirect;
    /**
     * @var OrderManagementInterface
     */
    private OrderManagementInterface $orderManagement;

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        OrderManagementInterface $orderManagement,
        RedirectInterface $redirect
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->orderManagement = $orderManagement;
    }

    public function execute()
    {
        $orderId = (int) $this->request->getParam('order_id');
        $this->orderManagement->cancel($orderId);
        $refererUrl = $this->redirect->getRefererUrl();
        /** @var \Magento\Framework\Controller\Result\Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl($refererUrl);

        return $redirect;
    }
}
