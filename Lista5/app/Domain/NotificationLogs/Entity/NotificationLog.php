<?php

namespace App\Domain\NotificationLogs\Entity;

use App\Domain\NotificationLogs\Enum\NotificationEnum;
use DateTime;
use Ramsey\Uuid\Uuid;

class NotificationLog
{
    public function __construct(
        private string $id,
        private string $user_id,
        private string $order_id,
        private string $message,
        private NotificationEnum $status,
        private int $attempts,
        private ?DateTime $created_at = null,
        private ?DateTime $updated_at = null,
        private ?DateTime $deleted_at = null,

    ) {
        $this->setMessage($message);
    }

    public static function createNew(
        string $user_id,
        string $order_id,
        string $message,
        NotificationEnum $status,
        int $attempts,
    ): self {
        return new self(
            Uuid::uuid4()->toString(),
            $user_id,
            $order_id,
            $message,
            $status,
            $attempts,
        );
    }

    private function setMessage(string $message)
    {
        if (empty(trim($message))) {
            throw new \InvalidArgumentException('A mensagem da notificação não pode ser vazia.');
        }
        $this->message = $message;
    }

    public function setStatusSent(): void
    {
        $this->status = NotificationEnum::SENT;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function getOrderId(): string
    {
        return $this->order_id;
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
            $data['id'],
            $data['user_id'],
            $data['order_id'],
            $data['message'],
            NotificationEnum::from($data['status']),
            $data['attempts'],
            isset($data['created_at']) ? new DateTime($data['created_at']) : null,
            isset($data['updated_at']) ? new DateTime($data['updated_at']) : null,
            isset($data['deleted_at']) ? new DateTime($data['deleted_at']) : null,

        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'message' => $this->message,
            'status' => $this->status->name,
            'attempts' => $this->attempts,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
