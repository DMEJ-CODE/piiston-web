<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis PIISTON - {{ $repair->vehicle->license_plate }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-50 font-sans">
    <div class="max-w-2xl mx-auto py-12 px-4">
        <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden border border-zinc-100">
            <!-- Header -->
            <div class="bg-zinc-900 p-10 text-white relative">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 18.5L4 16V8.5L12 4.5l8 4v7.5l-8 4z"/></svg>
                </div>
                <h1 class="text-3xl font-black tracking-tighter uppercase">Proposition de Réparation</h1>
                <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest mt-2">{{ $branch->name }}</p>
            </div>

            <div class="p-10 space-y-10">
                <!-- Vehicle Card -->
                <div class="flex items-center gap-6 p-6 bg-zinc-50 rounded-2xl">
                    <div class="size-16 rounded-2xl bg-zinc-900 text-white flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-zinc-900 uppercase">{{ $repair->vehicle->brand->name }} {{ $repair->vehicle->model->name }}</h2>
                        <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest">{{ $repair->vehicle->license_plate }}</p>
                    </div>
                </div>

                <!-- Summary -->
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-[3px]">Travaux Proposés</h3>
                    <div class="divide-y divide-zinc-100">
                        @foreach($repair->parts as $part)
                        <div class="py-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-black text-zinc-800">{{ $part->part_name }}</p>
                                <p class="text-[10px] text-zinc-500 uppercase tracking-wider">Quantité: {{ $part->quantity }}</p>
                            </div>
                            <span class="text-sm font-bold text-zinc-900">{{ number_format($part->total_price, 0, ',', ' ') }} F</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-zinc-900 p-8 rounded-[24px] text-white">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Total Estimé</p>
                            <p class="text-4xl font-black tracking-tighter">{{ number_format($estimate->total, 0, ',', ' ') }} <span class="text-lg">FCFA</span></p>
                        </div>
                        <p class="text-[10px] font-bold text-white/60 uppercase text-right leading-tight">Incluant taxes &<br>main d'œuvre</p>
                    </div>
                </div>

                <!-- Actions -->
                @if($estimate->status === 'PENDING')
                <div class="grid grid-cols-2 gap-4">
                    <form method="POST" action="{{ route('public.estimate.approve', $estimate->public_token) }}">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl transition-all">
                            Approuver
                        </button>
                    </form>
                    <button class="w-full bg-zinc-100 text-zinc-500 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-zinc-200 transition-all">
                        Refuser
                    </button>
                </div>
                @else
                <div class="bg-green-500/10 border border-green-500/20 p-6 rounded-2xl text-center">
                    <p class="text-green-600 font-black uppercase tracking-widest text-sm">Ce devis a été {{ $estimate->status }}</p>
                </div>
                @endif

                <p class="text-[10px] text-zinc-400 text-center uppercase tracking-widest">Ce lien expire le {{ $estimate->token_expires_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[2px]">Powered by PIISTON</p>
        </div>
    </div>
</body>
</html>
