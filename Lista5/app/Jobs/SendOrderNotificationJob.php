<?php

namespace App\Jobs;

use App\Services\Orders\ProcessOrderNotificationUseCase;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Entity\Order as DomainOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Domain\Users\Interfaces\UserRepositoryInterface;
use App\Mail\OrderNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\EntityNotFoundException;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private DomainOrder $order,
    ) {}

    public function handle(
        NotificationLogRepositoryInterface $notification_repository,
        OrderRepositoryInterface $order_repository,
        UserRepositoryInterface $user_repository,
    ): void {

        $user_id = $this->order->getUserId();

        $this->order->setStatusProcessing();
        $order_repository->update($this->order);

        $user = $user_repository->findById($user_id);

        if (! $user) {
            throw new EntityNotFoundException("Usuário {$user_id} não encontrado.");
        }

        $message = sprintf(
            'Informamos que o pedido referente ao produto "%s" (quantidade: %d) foi registrado com sucesso em nosso sistema.',
            $this->order->getProductName(),
            $this->order->getAmount()
        );

        Mail::to($user->email)->send(new OrderNotificationMail($this->order, $message));

        $this->order->setStatusCompleted();
        $order_repository->update($this->order);

        $notification_repository->save(DomainNotificationLog::createNew(
            $user_id,
            $this->order->getId(),
            $message,
            NotificationEnum::SENT,
            $this->attempts(),
        ));
    }

    public function failed(\Throwable $e): void
    {
        $notification_repository = app(NotificationLogRepositoryInterface::class);

        $notification_repository->save(DomainNotificationLog::createNew(
            $this->order->getUserId(),
            $this->order->getId(),
            'Falha ao enviar notificação: ' . $e->getMessage(),
            NotificationEnum::FAILED,
            $this->attempts(),
        ));
    }
}
