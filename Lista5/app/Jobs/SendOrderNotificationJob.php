<?php

namespace App\Jobs;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Services\Orders\ProcessOrderNotificationUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOrderNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        private DomainOrder $order,
    ) {}

    public function handle(ProcessOrderNotificationUseCase $use_case): void
    {
        $use_case->execute($this->order, $this->attempts());
    }

    public function failed(\Throwable $e): void
    {
        $notification_repository = app(NotificationLogRepositoryInterface::class);

        $notification_repository->save(DomainNotificationLog::createNew(
            $this->order->getUserId(),
            $this->order->getId(),
            'Falha ao enviar notificação: '.$e->getMessage(),
            NotificationEnum::FAILED,
            $this->attempts(),
        ));
    }
}
