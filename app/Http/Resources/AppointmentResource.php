<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'date'            => $this->date,
            'status'          => $this->status,
            'type'            => $this->type,
            'patient_id'      => $this->patient_id,
            'professional_id' => $this->professional_id,
            'schedule_id'     => $this->schedule_id,
            'observation'     => $this->observation,
            'created_at'      => $this->created_at,
            // Relations
            'patient'         => $this->whenLoaded('patient'),
            'professional'    => $this->whenLoaded('professional'),
            'schedule'        => $this->whenLoaded('schedule'),
            'payment'         => $this->whenLoaded('payment'),
            'precio'          => $this->whenLoaded('precio'),
            'reschedule'      => $this->whenLoaded('reschedule'),
        ];
    }
}
