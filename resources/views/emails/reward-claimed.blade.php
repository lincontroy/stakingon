<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward Claimed Successfully</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            color: #6b7280;
        }
        .card {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #374151;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .label {
            color: #6b7280;
            font-weight: 500;
        }
        .value {
            font-weight: 600;
            color: #111827;
        }
        .highlight {
            color: #10b981;
            font-size: 18px;
        }
        .balance-info {
            background-color: #e7f5ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #2563eb;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
        }
        .transaction-id {
            font-family: monospace;
            background-color: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="header">
      
        <div class="title">🎉 Reward Claimed Successfully!</div>
        <div class="subtitle">Your staking reward has been credited to your wallet</div>
    </div>

    <div class="card">
        <div class="card-title">Transaction Details</div>
        
        <div class="detail-row">
            <span class="label">Reward Amount:</span>
            <span class="value highlight">{{ number_format($stakingRecord->actual_reward, 8) }} {{ $stakingRecord->stakingPool->coin_type }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Staking Pool:</span>
            <span class="value">{{ $stakingRecord->stakingPool->name }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Original Stake:</span>
            <span class="value">{{ number_format($stakingRecord->amount, 8) }} {{ $stakingRecord->stakingPool->coin_type }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Transaction ID:</span>
            <span class="transaction-id">{{ $transaction['txid'] }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Date:</span>
            <span class="value">{{ now()->format('F j, Y, g:i a') }}</span>
        </div>
    </div>

    <div class="balance-info">
        <strong>💰 Updated Wallet Balance</strong><br>
        Available Balance: {{ number_format($wallet->available_balance, 8) }} {{ $wallet->coin_type }}<br>
        Total Earned to Date: {{ number_format($wallet->total_earned, 8) }} {{ $wallet->coin_type }}
    </div>

    <p>Your staked amount of <strong>{{ number_format($stakingRecord->amount, 8) }} {{ $stakingRecord->stakingPool->coin_type }}</strong> has been returned to your available balance, and your reward has been added to your total earnings.</p>


    <p>Ready to earn more? Check out our other staking pools and start earning passive income on your crypto assets.</p>

    <p>Need help or have questions? Our support team is always here for you.</p>

    <p>Happy staking! 🌟</p>

    <p>Best regards,<br>
    <strong>{{ config('app.name') }} Team</strong></p>

    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>This is an automated message. For any inquiries, please contact our support team.</p>
    </div>
</body>
</html>