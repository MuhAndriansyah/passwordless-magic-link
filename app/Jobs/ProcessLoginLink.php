<?php

namespace App\Jobs;

use App\Mail\MagicLoginLink;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessLoginLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $plainText;
    protected $expires;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($plainText, $expires, $email)
    {
        $this->plainText = $plainText;
        $this->expires = $expires;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new MagicLoginLink($this->plainText, $this->expires));
    }
}
