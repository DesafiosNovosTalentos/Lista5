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
    public function save(DomainNotificationLog $notificationLog): DomainNotificationLog
    {
        try {
            $model = NotificationLog::create([
                'id' => $notificationLog->getId(),
                'user_id' => $notificationLog->getUserId(),
                'order_id' => $notificationLog->getOrderId(),
                'message' => $notificationLog->getMessage(),
                'status' => $notificationLog->getStatus(),
                'attempts' => $notificationLog->getAttempts(),
            ]);

            return DomainNotificationLog::fromArray($model->toArray());
        } catch (QueryException) {
            throw new RepositoryException('Falha ao salvar o log de notificação.');
        }
    }

    public function findByUserId(string $userId): array
    {
        try {
            $logs = NotificationLog::where('user_id', $userId)
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

    public function update(DomainNotificationLog $notificationLog): DomainNotificationLog
    {
        try {
            NotificationLog::where('id', $notificationLog->getId())->update([
                'status' => $notificationLog->getStatus(),
                'attempts' => $notificationLog->getAttempts(),
            ]);

            return $notificationLog;
        } catch (QueryException) {
            throw new RepositoryException('Falha ao atualizar o log de notificação.');
        }
    }

    public function findById(string $id): ?DomainNotificationLog
    {
        try {
            $model = NotificationLog::find($id);

            if (! $model) {
                return null;
            }

            return DomainNotificationLog::fromArray($model->toArray());
        } catch (QueryException) {
            throw new RepositoryException('Falha ao consultar o log de notificação.');
        }
    }
}
