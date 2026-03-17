<?php

namespace Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProformaRestoredEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param \Payment\Models\Proforma $proforma
     * @param \Auth\Models\User $user
     */
    public function __construct(
        public readonly \Payment\Models\Proforma $proforma,
        public readonly \Auth\Models\User $user
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->proforma->company_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'manager.proforma';
    }

    public function broadcastWith(): array
    {
        return [
            'proforma' => [
                'id' => $this->proforma->id,
                'proforma_number' => $this->proforma->proforma_number,
                'total_amount' => $this->proforma->total_amount,
                'currency' => $this->proforma->currency,
            ],
            'user' => ['name' => $this->user->name],
            'action' => 'restored',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
