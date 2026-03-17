<?php

namespace Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Initialize the service created event.
     *
     * @param \Payment\Models\Service $service
     * @param \Auth\Models\User $user
     */
    public function __construct(
        public readonly \Payment\Models\Service $service,
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
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->service->company_id),
        ];
    }

    /**
     * Broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'manager.service';
    }

    /**
     * Data sent with the broadcast event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'service' => ['id' => $this->service->id, 'name' => $this->service->name, 'price' => $this->service->price],
            'user' => ['name' => $this->user->name],
            'action' => 'created',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
