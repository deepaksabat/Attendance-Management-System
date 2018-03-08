<?php
namespace App;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegistered implements ShouldBroadcast
{
    public $user;

    public function __construct($name)
    {

        $this->user = Messages::all();
    }

    public function broadcastOn()
    {
        return ['test-channel'];
    }
}
