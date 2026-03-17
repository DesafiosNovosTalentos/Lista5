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
        private string $user_id,
        private string $product_name,
        private int $amount,
        private OrderEnum $status,
        private ?DateTime $created_at = null,
        private ?DateTime $updated_at = null,
        private ?DateTime $deleted_at = null,
    ) {
        $this->setProductName($product_name);
        $this->setAmount($amount);
    }

    public static function createNew(string $user_id, string $product_name, int $amount): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            $user_id,
            $product_name,
            $amount,
            OrderEnum::PENDING
        );
    }

    private function setAmount(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('O valor do pedido deve ser maior que zero.');
        }
        $this->amount = $amount;
    }

    private function setProductName(string $product_name): void
    {
        if (empty(trim($product_name))) {
            throw new InvalidArgumentException('O nome do produto não pode ser vazio.');
        }
        $this->product_name = $product_name;
    }

    public function update(string $product_name, int $amount): void
    {
        $this->setProductName($product_name);
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
        return $this->user_id;
    }

    public function getProductName(): string
    {
        return $this->product_name;
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
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deleted_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_name' => $this->product_name,
            'amount' => $this->amount,
            'status' => $this->status->name,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
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
