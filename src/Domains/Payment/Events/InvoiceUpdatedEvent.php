<?php

namespace Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Initialize the invoice updated event.
     *
     * @param \Payment\Models\Invoice $invoice
     * @param \Auth\Models\User $user
     */
    public function __construct(
        public readonly \Payment\Models\Invoice $invoice,
        public readonly \Auth\Models\User $user
    ) {}

    /**
     * Broadcast channels for the event.
     *
     * @return array<int, PresenceChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->invoice->company_id),
        ];
    }

    /**
     * Broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'manager.invoice';
    }

    /**
     * Data sent with the broadcast event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'invoice' => [
                'id' => $this->invoice->id,
                'invoice_number' => $this->invoice->invoice_number,
                'total_amount' => $this->invoice->total_amount,
                'currency' => $this->invoice->currency,
                'status' => $this->invoice->status,
            ],
            'user' => ['name' => $this->user->name],
            'action' => 'updated',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
