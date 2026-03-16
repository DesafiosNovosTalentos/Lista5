<?php

namespace App\Http\Controllers;

use App\Services\NotificationLogs\ListNotificationsByUserUseCase;

class NotificationLogController extends Controller
{
    public function index(ListNotificationsByUserUseCase $useCase, string $userId)
    {
        $logs = $useCase->execute($userId);

        $data = [];
        foreach ($logs as $log) {
            $data[] = $log->toArray();
        }

        return response()->json($data);
    }
}
