<?php

namespace App\Jobs\Mailing;

use App\Models\Mailing;
use App\Services\Mailing\Service;
use App\Services\Mailing\Api;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $id;
    protected string $phone;
    protected string $msg;

    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(int $id, string $phone, string $msg)
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->msg = $msg;
    }

    public function backoff()
    {
        return (new Service())->getRandDelay();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new Api\Service())->sendMsg($this->phone, $this->msg);
        (new Service())->setStatus($this->id, Mailing::STATUS_GOOD);
    }

    public function failed(\Exception $exception)
    {
        (new Service())->setStatus($this->id, Mailing::STATUS_FAIL);
    }
}
