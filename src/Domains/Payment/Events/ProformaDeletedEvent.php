<?php

namespace Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProformaDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $invoiceId;
    private string $proformaNumber;
    private int $companyId;

    /**
     * @param \Payment\Models\Proforma $proforma
     * @param \Auth\Models\User $user
     */
    public function __construct(
        \Payment\Models\Proforma $proforma,
        public readonly \Auth\Models\User $user
    ) {
        $this->invoiceId = $proforma->id;
        $this->proformaNumber = $proforma->proforma_number;
        $this->companyId = $proforma->company_id;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->companyId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'manager.proforma';
    }

    public function broadcastWith(): array
    {
        return [
            'proforma' => ['id' => $this->invoiceId, 'proforma_number' => $this->proformaNumber],
            'user' => ['name' => $this->user->name],
            'action' => 'deleted',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
