<?php

namespace Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillingUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Initialize the billing updated event.
     *
     * @param \Payment\Models\Billing $billing
     * @param \Auth\Models\User $user
     */
    public function __construct(
        public readonly \Payment\Models\Billing $billing,
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
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->billing->company_id),
        ];
    }

    /**
     * Broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'manager.billing';
    }

    /**
     * Data sent with the broadcast event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'billing' => ['id' => $this->billing->id, 'tax_id' => $this->billing->tax_id],
            'user' => ['name' => $this->user->name],
            'action' => 'updated',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
