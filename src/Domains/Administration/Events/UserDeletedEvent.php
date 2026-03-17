<?php

namespace Administration\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $userId;
    private string $userName;
    private string $userEmail;
    private int $companyId;

    /**
     * Initialize the user deleted event.
     *
     * @param \Administration\Models\User $deletedUser
     * @param \Auth\Models\User $user
     */
    public function __construct(
        \Administration\Models\User $deletedUser,
        public readonly \Auth\Models\User $user
    ) {
        $this->userId = $deletedUser->id;
        $this->userName = $deletedUser->name;
        $this->userEmail = $deletedUser->email;
        $this->companyId = $deletedUser->company_id;
    }

    /**
     * Broadcast channels for the event.
     *
     * @return array<int, PresenceChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(config('broadcasting.channel') . '.company.' . $this->companyId),
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
            'target_user' => ['id' => $this->userId, 'name' => $this->userName, 'email' => $this->userEmail],
            'user' => ['name' => $this->user->name],
            'action' => 'deleted',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
