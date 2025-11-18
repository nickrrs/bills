<?php

namespace App\Services;

use App\Models\Wallet;

class WalletService
{
    public function getAllWallets($payload = [])
    {
        $per_page = $payload['per_page'] ?? 10;
        $page = $payload['page'] ?? 1;
        $order_by = $payload['order_by'] ?? 'is_default';
        $order_direction = $payload['order_direction'] ?? 'desc';
        $search = $payload['search'] ?? null;
        $user_id = $payload['user_id'] ?? null;

        $query = Wallet::query();

        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->orderBy($order_by, $order_direction);

        return $query->paginate($per_page, ['*'], 'page', $page);
    }

    public function getTotalBalance($userId)
    {
        return Wallet::where('user_id', $userId)
            ->where('active', true)
            ->sum('balance');
    }

    public function createWallet(array $data)
    {
        return Wallet::create($data);
    }

    public function updateWallet(Wallet $wallet, array $data)
    {
        if ($data['is_default']) {
            Wallet::where('user_id', $wallet->user_id)->update(['is_default' => false]);
        }

        $wallet->update($data);
        return $wallet;
    }

    public function deleteWallet(Wallet $wallet)
    {
        $wallet->delete();
    }
}
