<?php

namespace Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $serviceId;
    private string $serviceName;
    private int $companyId;

    /**
     * Initialize the service deleted event.
     *
     * @param \Payment\Models\Service $service
     * @param \Auth\Models\User $user
     */
    public function __construct(
        \Payment\Models\Service $service,
        public readonly \Auth\Models\User $user
    ) {
        $this->serviceId = $service->id;
        $this->serviceName = $service->name;
        $this->companyId = $service->company_id;
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
            'service' => ['id' => $this->serviceId, 'name' => $this->serviceName],
            'user' => ['name' => $this->user->name],
            'action' => 'deleted',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
