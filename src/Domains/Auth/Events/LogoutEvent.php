<?php

namespace Auth\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogoutEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new logout event instance.
     *
     * @param \Auth\Models\User $user
     */
    public function __construct(
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
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->user->company_id),
        ];
    }

    /**
     * Broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'auth.user';
    }

    /**
     * Data sent with the broadcast event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'user' => ['name' => $this->user->name],
            'action' => 'logout',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
