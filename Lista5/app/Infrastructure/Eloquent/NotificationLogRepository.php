<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Enum\NotificationEnum;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\NotificationLog;
use Illuminate\Database\QueryException;

class NotificationLogRepository implements NotificationLogRepositoryInterface
{
    public function save(DomainNotificationLog $notification_log): DomainNotificationLog
    {
        try {
            $model = NotificationLog::create([
                'id' => $notification_log->getId(),
                'user_id' => $notification_log->getUserId(),
                'order_id' => $notification_log->getOrderId(),
                'message' => $notification_log->getMessage(),
                'status' => $notification_log->getStatus(),
                'attempts' => $notification_log->getAttempts(),
            ]);

            return DomainNotificationLog::fromArray($model->toArray());
        } catch (QueryException) {
            throw new RepositoryException('Falha ao salvar o log de notificação.');
        }
    }

    public function findByUserId(string $user_id): array
    {
        try {
            $logs = NotificationLog::where('user_id', $user_id)
                ->latest()
                ->get();

            $result = [];
            foreach ($logs as $model) {
                $result[] = DomainNotificationLog::fromArray($model->toArray());
            }

            return $result;
        } catch (QueryException) {
            throw new RepositoryException('Falha ao consultar logs de notificação.');
        }
    }

    public function findFailed(): array
    {
        try {
            $logs = NotificationLog::where('status', NotificationEnum::FAILED)
                ->get();

            $result = [];
            foreach ($logs as $model) {
                $result[] = DomainNotificationLog::fromArray($model->toArray());
            }

            return $result;
        } catch (QueryException) {
            throw new RepositoryException('Falha ao consultar notificações com falha.');
        }
    }
}
