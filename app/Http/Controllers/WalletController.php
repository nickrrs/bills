<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Http\Requests\BulkDeleteWalletRequest;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletController extends Controller
{
    private $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index()
    {
        return Inertia::render('Wallets');
    }

    public function create()
    {
        return Inertia::render('Wallets/Create');
    }

    public function store(StoreWalletRequest $request)
    {
        $this->walletService->createWallet($request->validated());
        return redirect()->route('wallets')->with('success', 'carteira criada com sucesso');
    }

    // API Methods
    public function apiIndex(Request $request): JsonResponse
    {
        $payload = $request->all();
        $payload['user_id'] = $request->user()->id;
        $wallets = $this->walletService->getAllWallets($payload);
        $totalBalance = $this->walletService->getTotalBalance($request->user()->id);

        return response()->json([
            'data' => $wallets->items(),
            'total' => $wallets->total(),
            'per_page' => $wallets->perPage(),
            'current_page' => $wallets->currentPage(),
            'last_page' => $wallets->lastPage(),
            'from' => $wallets->firstItem(),
            'to' => $wallets->lastItem(),
            'total_balance' => $totalBalance,
        ]);
    }

    public function apiStore(StoreWalletRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $wallet = $this->walletService->createWallet($data);
        return response()->json($wallet, 201);
    }

    public function apiUpdate(UpdateWalletRequest $request, Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        $wallet = $this->walletService->updateWallet($wallet, $data);
        return response()->json($wallet);
    }

    public function apiDestroy(Request $request, Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->walletService->deleteWallet($wallet);
        return response()->json(['message' => 'carteira deletada com sucesso']);
    }

    public function apiBulkDestroy(BulkDeleteWalletRequest $request): JsonResponse
    {
        $deletedCount = $this->walletService->bulkDeleteWallets(
            $request->input('ids'),
            $request->user()->id
        );

        return response()->json([
            'message' => 'carteiras deletadas com sucesso',
            'deleted_count' => $deletedCount,
        ]);
    }
}
