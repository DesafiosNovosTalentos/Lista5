<?php

namespace App\Domain\Orders\Entity;

use App\Domain\Orders\Enums\OrderEnum;
use DateTime;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class Order
{
    public function __construct(
        private string $id,
        private string $userId,
        private string $productName,
        private int $amount,
        private OrderEnum $status,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null,
        private ?DateTime $deletedAt = null,
    ) {
        $this->setProductName($productName);
        $this->setAmount($amount);
    }

    public static function createNew(string $userId, string $productName, int $amount): self
    {
        return new self(
            id: Uuid::uuid4()->toString(),
            userId: $userId,
            productName: $productName,
            amount: $amount,
            status: OrderEnum::PENDING
        );
    }

    private function setAmount(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('O valor do pedido deve ser maior que zero.');
        }
        $this->amount = $amount;
    }

    private function setProductName(string $productName): void
    {
        if (empty(trim($productName))) {
            throw new InvalidArgumentException('O nome do produto não pode ser vazio.');
        }
        $this->productName = $productName;
    }

    public function update(string $productName, int $amount): void
    {
        $this->setProductName($productName);
        $this->setAmount($amount);
    }

    public function setStatusProcessing(): void
    {
        $this->status = OrderEnum::PROCESSING;
    }

    public function setStatusCompleted(): void
    {
        $this->status = OrderEnum::COMPLETED;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getStatus(): OrderEnum
    {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'product_name' => $this->productName,
            'amount' => $this->amount,
            'status' => $this->status->name,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['user_id'],
            $data['product_name'],
            $data['amount'],
            OrderEnum::from($data['status']),
            isset($data['created_at']) ? new DateTime($data['created_at']) : null,
            isset($data['updated_at']) ? new DateTime($data['updated_at']) : null,
            isset($data['deleted_at']) ? new DateTime($data['deleted_at']) : null,
        );
    }
}
