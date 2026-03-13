<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'amount'         => $this->amount,
            'date'           => $this->date,
            'type'           => $this->type,
            'activo'         => $this->activo,
            'voucher'        => $this->voucher,
            'voucher_issued' => $this->voucher_issued,
            'observation'    => $this->observation,
            'moneda'         => $this->moneda,
            'appointment_id' => $this->appointment_id,
            'user_id'        => $this->user_id,
            'idSede'         => $this->idSede,
            'created_at'     => $this->created_at,
            // Relations
            'usuario'        => $this->whenLoaded('usuario'),
        ];
    }
}
