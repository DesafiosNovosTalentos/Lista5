<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\NotificationLogs\Dto\CreateNotificationLogDTO;
use App\Domain\NotificationLogs\Entity\NotificationLog as DomainNotificationLog;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\NotificationLogs\Enum\NotificationEnum;

use App\Exceptions\RepositoryException;
use App\Models\NotificationLog;
use Illuminate\Database\QueryException;
use Ramsey\Uuid\Uuid;

class NotificationLogRepository implements NotificationLogRepositoryInterface
{
    public function save(CreateNotificationLogDTO $dto): DomainNotificationLog
    {
        try {
            $model = NotificationLog::create([
                'id' => Uuid::uuid4()->toString(),
                'user_id' => $dto->userId,
                'order_id' => $dto->orderId,
                'message' => $dto->message,
                'status' => $dto->status,
                'attempts' => $dto->attempts,
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
}
