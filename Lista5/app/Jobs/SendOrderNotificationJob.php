<?php

namespace App\Jobs;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Domain\Users\UserRepositoryInterface;

use App\Mail\OrderNotificationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private DomainOrder $order,
        private ?string $notificationLogId = null,
    ) {}

    public function handle(
        NotificationLogRepositoryInterface $notification_repository,
        OrderRepositoryInterface $order_repository,
        UserRepositoryInterface $user_repository,

    ): void {
        $this->order->setStatusProcessing();
        $order_repository->update($this->order);

        $user = $user_repository->findById($this->order->getUserId());

        $message = sprintf(
            'Informamos que o pedido referente ao produto "%s" (quantidade: %d) foi registrado com sucesso em nosso sistema.',
            $this->order->getProductName(),
            $this->order->getAmount()
        );

        Mail::to($user->email)->send(new OrderNotificationMail($this->order, $message));

        DB::transaction(function () use ($order_repository, $notification_repository, $message): void {
            $this->order->setStatusCompleted();
            $order_repository->update($this->order);

            if ($this->notificationLogId) {
                $log = $notification_repository->findById($this->notificationLogId);
                $log->setStatusSent();
                $notification_repository->update($log);
            } else {
                $notification_repository->save(DomainNotificationLog::createNew(
                    userId: $this->order->getUserId(),
                    orderId: $this->order->getId(),
                    message: $message,
                    status: NotificationEnum::SENT,
                    attempts: $this->tries,
                ));
            }
        });
    }

    public function failed(\Throwable $e): void
    {
        $notification_repository = app(NotificationLogRepositoryInterface::class);

        $notification_repository->save(DomainNotificationLog::createNew(
            userId: $this->order->getUserId(),
            orderId: $this->order->getId(),
            message: 'Falha ao enviar notificação: ' . $e->getMessage(),
            status: NotificationEnum::FAILED,
            attempts: $this->tries,
        ));
    }
}
