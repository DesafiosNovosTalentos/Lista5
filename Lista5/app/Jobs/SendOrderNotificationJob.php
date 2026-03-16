<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Domain\NotificationLogs\Dto\CreateNotificationLogDTO;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;

use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Mail\OrderNotificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $attempts = 3;
    public int $backoff = 60;

    public function __construct(private DomainOrder $order) {}

    public function handle(NotificationLogRepositoryInterface $repository): void
    {
        $user = User::findOrFail($this->order->getUserId());

        $message = sprintf(
            'Seu pedido para o produto "%s" no valor de R$ %d foi criado com sucesso.',
            $this->order->getProductName(),
            $this->order->getAmount(),
        );

        Mail::to($user->email)->send(new OrderNotificationMail($this->order, $message));

        $repository->save(new CreateNotificationLogDTO(
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

        $repository->save(new CreateNotificationLogDTO(
            userId: $this->order->getUserId(),
            orderId: $this->order->getId(),
            message: 'Falha ao enviar notificação: ' . $e->getMessage(),
            status: NotificationEnum::FAILED,
            attempts: $this->attempts,
        ));
    }
}
