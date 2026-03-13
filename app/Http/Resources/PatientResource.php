<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'dni'                   => $this->dni,
            'name'                  => $this->name,
            'nombres'               => $this->nombres,
            'phone'                 => $this->phone,
            'gender'                => $this->gender,
            'birth_date'            => $this->birth_date,
            'occupation'            => $this->occupation,
            'marital_status'        => $this->marital_status,
            'instruction_degree'    => $this->instruction_degree,
            'recomendation'         => $this->recomendation,
            'recomendacion_comentario' => $this->recomendacion_comentario,
            'activo'                => $this->activo,
            'discharge'             => $this->discharge,
            'sos'                   => $this->sos,
            'hobbies'               => $this->hobbies,
            'created_at'            => $this->created_at,
            // Relations (only loaded when present — avoids N+1)
            'address'               => $this->whenLoaded('address'),
            'relative'              => $this->whenLoaded('relative'),
            'semaforo'              => $this->whenLoaded('semaforo'),
            'acuerdos'              => $this->whenLoaded('acuerdos'),
            'prescriptions'         => $this->whenLoaded('prescriptions'),
            'initial_psychiatric_history'  => $this->whenLoaded('initial_psychiatric_history'),
            'initial_psychological_history' => $this->whenLoaded('initial_psychological_history'),
            // Extra computed fields set on the model in controller loops
            'triajes'               => $this->when(isset($this->triajes), $this->triajes),
            'reprogramaciones'      => $this->when(isset($this->reprogramaciones), $this->reprogramaciones),
        ];
    }
}
