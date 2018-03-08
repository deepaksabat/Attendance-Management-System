<?php

namespace App\Events;

use Auth;
use App\Messages;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $response;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Messages $messages)
    {
        if(isset($messages->company_id) && $messages->company_id == Auth::user()->company_id)
        $this->response = Messages::with('User')->find($messages->id)->toArray();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        if(isset($this->response['company_id']) && $this->response['company_id'] == Auth::user()->company_id)
            return ['messageAction'];
        else
            return array();
    }
}
