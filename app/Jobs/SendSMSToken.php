<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider;

class SendSMSToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * A user instance.
     *
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param  \MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider  $provider
     * @return void
     */
    public function handle(TwoFactorProvider $provider) : void
    {
        $provider->sendSMSToken($this->user);
    }
}
