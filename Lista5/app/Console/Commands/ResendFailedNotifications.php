<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Jobs\SendOrderNotificationJob;

class ResendFailedNotifications extends Command
{
    protected $signature = 'notifications:retry-failed';

    public function handle(
        NotificationLogRepositoryInterface $notificationRepository,
        OrderRepositoryInterface $orderRepository,
    ): void {
        $failed = $notificationRepository->findFailed();

        if (empty($failed)) {
            $this->info('Nenhuma notificação com falha encontrada.');
            return;
        }

        $this->info(count($failed) . ' notificação(ões) encontrada(s). Reenviando...');

        foreach ($failed as $log) {
            $order = $orderRepository->findById($log->getOrderId());

            if ($order === null) {
                $this->warn("Pedido {$log->getOrderId()} não encontrado. Pulando.");
                continue;
            }

            SendOrderNotificationJob::dispatch($order);
            $this->line("  → Job despachado para o pedido {$log->getOrderId()}");
        }

        $this->info('Concluído.');
    }
}
