<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public static function log(string $action, string $model, int $modelId, array $oldValues = [], array $newValues = []): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'old_values' => !empty($oldValues) ? json_encode($oldValues) : null,
            'new_values' => !empty($newValues) ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function logCitizenCreated($citizen): void
    {
        self::log('created', 'Citizen', $citizen->id, [], $citizen->toArray());
    }

    public static function logCitizenUpdated($citizen, array $oldValues): void
    {
        self::log('updated', 'Citizen', $citizen->id, $oldValues, $citizen->toArray());
    }

    public static function logCitizenDeleted($citizen): void
    {
        self::log('deleted', 'Citizen', $citizen->id, $citizen->toArray(), []);
    }

    public static function logApplicationCreated($application): void
    {
        self::log('created', 'Application', $application->id, [], $application->toArray());
    }

    public static function logApplicationStatusChanged($application, string $oldStatus): void
    {
        self::log('status_changed', 'Application', $application->id, 
            ['status' => $oldStatus], 
            ['status' => $application->status]
        );
    }

    public static function logPhotoUploaded($citizen): void
    {
        self::log('photo_uploaded', 'Citizen', $citizen->id, [], ['photo_path' => $citizen->photo_path]);
    }

    public static function logBiometricCaptured($biometric): void
    {
        self::log('biometric_captured', 'Biometric', $biometric->id, [], [
            'citizen_id' => $biometric->citizen_id,
            'application_id' => $biometric->application_id
        ]);
    }

    public static function logDocumentUploaded($document): void
    {
        self::log('document_uploaded', 'Document', $document->id, [], [
            'application_id' => $document->application_id,
            'document_type' => $document->document_type
        ]);
    }

    public static function logIdCardGenerated($citizen): void
    {
        self::log('id_card_generated', 'Citizen', $citizen->id, [], []);
    }
}
