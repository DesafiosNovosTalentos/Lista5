<?php

namespace App\Domain\NotificationLogs\Entity;

use App\Domain\NotificationLogs\Enum\NotificationEnum;
use DateTime;
use Ramsey\Uuid\Uuid;

class NotificationLog
{
    public function __construct(
        private string $id,
        private string $userId,
        private string $orderId,
        private string $message,
        private NotificationEnum $status,
        private int $attempts,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null,
        private ?DateTime $deletedAt = null,

    ) {
        $this->setMessage($message);
    }

    public static function createNew(
        string $userId,
        string $orderId,
        string $message,
        NotificationEnum $status,
        int $attempts,
    ): self {
        return new self(
            id: Uuid::uuid4()->toString(),
            userId: $userId,
            orderId: $orderId,
            message: $message,
            status: $status,
            attempts: $attempts,
        );
    }

    private function setMessage(string $message)
    {
        if (empty(trim($message))) {
            throw new \InvalidArgumentException('A mensagem da notificação não pode ser vazia.');
        }
        $this->message = $message;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): NotificationEnum
    {
        return $this->status;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            userId: $data['user_id'],
            orderId: $data['order_id'],
            message: $data['message'],
            status: NotificationEnum::from($data['status']),
            attempts: $data['attempts'],
            createdAt: isset($data['created_at']) ? new DateTime($data['created_at']) : null,
            updatedAt: isset($data['updated_at']) ? new DateTime($data['updated_at']) : null,
            deletedAt: isset($data['deleted_at']) ? new DateTime($data['deleted_at']) : null,

        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'order_id' => $this->orderId,
            'message' => $this->message,
            'status' => $this->status->name,
            'attempts' => $this->attempts,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
