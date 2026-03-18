<?php

namespace App\Console\Commands;

use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Jobs\SendOrderNotificationJob;
use Illuminate\Console\Command;

class ResendFailedNotifications extends Command
{
    protected $signature = 'notifications:retry-failed';

    protected $description = 'Reenvia notificações com status failed';

    public function handle(
        NotificationLogRepositoryInterface $notification_repository,
        OrderRepositoryInterface $order_repository,
    ): void {
        $failed = $notification_repository->findFailed();

        if (empty($failed)) {
            $this->info('Nenhuma notificação com falha encontrada.');

            return;
        }

        $this->info(count($failed).' notificação(ões) encontrada(s). Reenviando...');

        foreach ($failed as $log) {
            $order_id = $log->getOrderId();
            $order = $order_repository->findById($order_id);

            if ($order === null) {
                $this->warn("Pedido {$order_id} não encontrado. Pulando.");

                continue;
            }

            SendOrderNotificationJob::dispatch($order);
            $this->line("Job despachado para o pedido {$order_id}.");
        }

        $this->info('Concluído.');
    }
}
