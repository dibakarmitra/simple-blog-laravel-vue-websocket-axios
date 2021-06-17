<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
      $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('post.'.$this->comment->post->id);
    }

    public function broadcastWith()
    {
      return [
        'body' => $this->comment->body,
        'created_at' => $this->comment->created_at->diffForHumans(),
        'user' => [
          'name' => $this->comment->user->name,
          'avatar' => 'http://lorempixel.com/50/50'
        ]
      ];
    }
}
