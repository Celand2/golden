@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Mes Notifications</h1>
        @if($unreadCount > 0)
            <form action="{{ route('client.notifications.read-all') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Marquer tout comme lu
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <p class="text-gray-700">Vous n'avez pas encore de notifications.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div class="bg-white border-l-4 {{ $notification->is_read ? 'border-gray-300 bg-gray-50' : 'border-blue-500 bg-blue-50' }} p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg {{ $notification->is_read ? 'text-gray-700' : 'text-blue-900' }}">
                                {{ $notification->title }}
                            </h3>
                            <p class="text-gray-700 mt-1">{{ $notification->message }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex gap-2 ml-4">
                            @if(!$notification->is_read)
                                <form action="{{ route('client.notification.read', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-sm bg-green-500 text-white rounded hover:bg-green-600" title="Marquer comme lu">
                                        ✓
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('client.notification.delete', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette notification?')">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600" title="Supprimer">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Badge de type de notification --}}
                    <div class="mt-3">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                            {{ match($notification->type) {
                                'deposit_approved' => 'bg-green-100 text-green-800',
                                'deposit_rejected' => 'bg-red-100 text-red-800',
                                'withdrawal_approved' => 'bg-green-100 text-green-800',
                                'withdrawal_rejected' => 'bg-red-100 text-red-800',
                                'commission_received' => 'bg-yellow-100 text-yellow-800',
                                'daily_gain' => 'bg-blue-100 text-blue-800',
                                'daily_claim' => 'bg-purple-100 text-purple-800',
                                'vip_expired' => 'bg-orange-100 text-orange-800',
                                'new_referral' => 'bg-indigo-100 text-indigo-800',
                                'premium_upgrade' => 'bg-pink-100 text-pink-800',
                                default => 'bg-gray-100 text-gray-800',
                            } }}">
                            {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@if (session('success'))
    <div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@endsection
