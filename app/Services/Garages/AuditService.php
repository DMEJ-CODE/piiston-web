<?php

namespace App\Services\Garages;

use App\Models\Garages\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function log(string $entityType, int $entityId, string $action, ?array $oldValues = null, ?array $newValues = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getEntityHistory(string $entityType, int $entityId, int $perPage = 15)
    {
        return AuditLog::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getUserActions(User $user, int $perPage = 15)
    {
        return AuditLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
