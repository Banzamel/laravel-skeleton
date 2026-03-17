<?php

namespace Administration\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Role;

class RoleDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $roleId;
    private string $roleName;
    private int $companyId;

    /**
     * Initialize the role deleted event.
     *
     * @param Role $role
     * @param \Auth\Models\User $user
     */
    public function __construct(
        Role $role,
        public readonly \Auth\Models\User $user
    ) {
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->companyId = $role->company_id;
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
        return 'secretariat.role';
    }

    /**
     * Data sent with the broadcast event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'role' => ['id' => $this->roleId, 'name' => $this->roleName],
            'user' => ['name' => $this->user->name],
            'action' => 'deleted',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
