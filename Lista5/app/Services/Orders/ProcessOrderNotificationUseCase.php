<?php

namespace App\Services\Orders;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Domain\Users\Interfaces\UserRepositoryInterface;
use App\Exceptions\EntityNotFoundException;
use App\Mail\OrderNotificationMail;
use Illuminate\Support\Facades\Mail;

class ProcessOrderNotificationUseCase
{
    public function __construct(
        private NotificationLogRepositoryInterface $notification_repository,
        private OrderRepositoryInterface $order_repository,
        private UserRepositoryInterface $user_repository,
    ) {}

    public function execute(DomainOrder $order, int $attempts): void
    {
        $user_id = $order->getUserId();

        $order->setStatusProcessing();
        $this->order_repository->update($order);

        $user = $this->user_repository->findById($user_id);

        if (! $user) {
            throw new EntityNotFoundException("Usuário {$user_id} não encontrado.");
        }

        $message = sprintf(
            'Informamos que o pedido referente ao produto "%s" (quantidade: %d) foi registrado com sucesso em nosso sistema.',
            $order->getProductName(),
            $order->getAmount()
        );

        Mail::to($user->email)->send(new OrderNotificationMail($order, $message));

        $order->setStatusCompleted();
        $this->order_repository->update($order);

        $this->notification_repository->save(DomainNotificationLog::createNew(
            $user_id,
            $order->getId(),
            $message,
            NotificationEnum::SENT,
            $attempts,
        ));
    }
}
