<?php

namespace Administration\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Initialize the user created event.
     *
     * @param \Administration\Models\User $createdUser
     * @param \Auth\Models\User $user
     */
    public function __construct(
        public readonly \Administration\Models\User $createdUser,
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
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->createdUser->company_id),
        ];
    }

    /**
     * Broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'secretariat.user';
    }

    /**
     * Data sent with the broadcast event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'target_user' => ['id' => $this->createdUser->id, 'name' => $this->createdUser->name, 'email' => $this->createdUser->email],
            'user' => ['name' => $this->user->name],
            'action' => 'created',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
