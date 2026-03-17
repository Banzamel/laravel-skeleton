<?php

namespace Administration\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAvatarUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Initialize the user avatar updated event.
     *
     * @param \Administration\Models\User $updatedUser
     * @param \Auth\Models\User $user
     */
    public function __construct(
        public readonly \Administration\Models\User $updatedUser,
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
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->updatedUser->company_id),
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
            'target_user' => ['id' => $this->updatedUser->id, 'name' => $this->updatedUser->name, 'avatar_url' => $this->updatedUser->avatar_url],
            'user' => ['name' => $this->user->name],
            'action' => 'avatar_updated',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
