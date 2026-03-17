<?php

namespace App\Http\Controllers;

use App\Services\NotificationLogs\ListNotificationsByUserUseCase;

class NotificationLogController extends Controller
{
    public function index(ListNotificationsByUserUseCase $use_case, string $user_id)
    {
        $logs = $use_case->execute($user_id);

        $data = [];
        foreach ($logs as $log) {
            $data[] = $log->toArray();
        }

        return response()->json($data);
    }
}
