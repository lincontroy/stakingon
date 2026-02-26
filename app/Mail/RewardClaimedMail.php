<?php

namespace App\Mail;

use App\Models\StakingRecord;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RewardClaimedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stakingRecord;
    public $wallet;
    public $transaction;

    /**
     * Create a new message instance.
     */
    public function __construct(StakingRecord $stakingRecord, Wallet $wallet, $transaction)
    {
        $this->stakingRecord = $stakingRecord;
        $this->wallet = $wallet;
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Your Staking Reward Has Been Successfully Claimed!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reward-claimed',
        );
    }
}