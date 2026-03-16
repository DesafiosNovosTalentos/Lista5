<?php

namespace App\Jobs;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Mail\OrderNotificationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(private DomainOrder $order) {}

    public function handle(NotificationLogRepositoryInterface $repository): void
    {
        $user = User::findOrFail($this->order->getUserId());

        $message = sprintf(
            'Informamos que o pedido referente ao produto "%s" (quantidade: %d) foi registrado com sucesso em nosso sistema.',
            $this->order->getProductName(),
            $this->order->getAmount()
        );

        Mail::to($user->email)->send(new OrderNotificationMail($this->order, $message));

        $repository->save(DomainNotificationLog::createNew(
            userId: $this->order->getUserId(),
            orderId: $this->order->getId(),
            message: $message,
            status: NotificationEnum::SENT,
            attempts: $this->attempts(),
        ));
    }

    public function failed(\Throwable $e): void
    {

        $repository = app(NotificationLogRepositoryInterface::class);

        $repository->save(DomainNotificationLog::createNew(
            userId: $this->order->getUserId(),
            orderId: $this->order->getId(),
            message: 'Falha ao enviar notificação: '.$e->getMessage(),
            status: NotificationEnum::FAILED,
            attempts: $this->attempts(),
        ));
    }
}
