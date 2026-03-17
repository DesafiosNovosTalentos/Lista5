<?php

namespace App\Jobs;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Domain\Users\Interfaces\UserRepositoryInterface;
use App\Mail\OrderNotificationMail;
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
        private ?string $notification_log_id = null,
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

            if ($this->notification_log_id) {
                $log = $notification_repository->findById($this->notification_log_id);
                $log->setStatusSent();
                $notification_repository->update($log);
            } else {
                $notification_repository->save(DomainNotificationLog::createNew(
                    $this->order->getUserId(),
                    $this->order->getId(),
                    $message,
                    NotificationEnum::SENT,
                    $this->tries,
                ));
            }
        });
    }

    public function failed(\Throwable $e): void
    {
        $notification_repository = app(NotificationLogRepositoryInterface::class);

        $notification_repository->save(DomainNotificationLog::createNew(
            $this->order->getUserId(),
            $this->order->getId(),
            'Falha ao enviar notificação: ' . $e->getMessage(),
            NotificationEnum::FAILED,
            $this->tries,
        ));
    }
}
