<?php

namespace FileManager\Events;

use FileManager\Models\FileManagerPath;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param \Auth\Models\User $user
     * @param \FileManager\Models\FileManagerPath $filePath
     */
    public function __construct(
        public readonly \Auth\Models\User $user,
        public readonly FileManagerPath $filePath,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel(config('broadcasting.channel') . '.company' . '.' . $this->user->company_id),
        ];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'file.uploaded';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'user' => [
                'name' => $this->user->name,
            ],
            'file' => [
                'id' => $this->filePath->id,
                'name' => $this->filePath->name,
                'type' => $this->filePath->type->value,
            ],
            'message' => 'file.uploaded',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
