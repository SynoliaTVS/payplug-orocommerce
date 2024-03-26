<?php

declare(strict_types=1);

namespace Payplug\Bundle\PaymentBundle\Controller;

use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Payplug\Bundle\PaymentBundle\Method\Provider\PayplugMethodProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PaymentTransactionController extends AbstractController
{
    #[Route(path: '/info/{paymentTransactionId}/', name: 'payplug_payment_transaction_info')]
    #[ParamConverter('paymentTransaction', class: 'Oro\Bundle\PaymentBundle\Entity\PaymentTransaction', options: ['id' => 'paymentTransactionId'])]
    #[Template]
    public function infoAction(PaymentTransaction $paymentTransaction, PayplugMethodProvider $payplugMethodProvider)
    {
        $paymentMethod = $payplugMethodProvider->getPaymentMethod(
            $paymentTransaction->getPaymentMethod()
        );

        $payplugResponse = $paymentMethod->getTransactionInfos($paymentTransaction);

        return ['payplugResponse' => $payplugResponse];
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(
            [
                PayplugMethodProvider::class,
            ],
            parent::getSubscribedServices()
        );
    }
}
