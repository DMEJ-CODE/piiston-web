<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture PIISTON #{{ $invoice->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; color: black; }
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-12 shadow-lg rounded-xl border border-gray-200 print:shadow-none print:border-none">
        <!-- Header -->
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase mb-1">PIISTON</h1>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $branch->name }}</p>
                <div class="mt-4 text-xs text-gray-600 space-y-1">
                    <p>{{ $branch->company->address_id ? 'Adresse succursale...' : '' }}</p>
                    <p>Tél: {{ $branch->phone }}</p>
                    <p>Email: {{ $branch->email }}</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black text-blue-600 uppercase mb-2">FACTURE</h2>
                <div class="text-xs space-y-1">
                    <p class="font-bold">N° : <span class="text-gray-900">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                    <p class="font-bold">Date : <span class="text-gray-900">{{ $invoice->created_at->format('d/m/Y') }}</span></p>
                    <p class="font-bold">RO : <span class="text-gray-900">#{{ $invoice->repairOrder->id }}</span></p>
                </div>
            </div>
        </div>

        <div class="h-px bg-gray-100 mb-12"></div>

        <!-- Billing Info -->
        <div class="grid grid-cols-2 gap-12 mb-12">
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Facturé à</h3>
                <div class="text-sm">
                    <p class="font-black text-gray-900">{{ $invoice->repairOrder->garageCustomer->user->name }}</p>
                    <p class="text-gray-600 mt-1">{{ $invoice->repairOrder->vehicle->brand->name }} {{ $invoice->repairOrder->vehicle->model->name }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">{{ $invoice->repairOrder->vehicle->license_plate }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Détails Véhicule</h3>
                <div class="text-sm grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-bold text-gray-400 uppercase">Kilométrage</span>
                        <span class="font-bold">{{ $invoice->repairOrder->checkIn->mileage ?? 'N/A' }} KM</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-bold text-gray-400 uppercase">VIN</span>
                        <span class="font-bold">{{ substr($invoice->repairOrder->vehicle->vin, -8) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left mb-12">
            <thead>
                <tr class="border-b-2 border-gray-900">
                    <th class="py-4 text-[10px] font-black text-gray-900 uppercase tracking-widest">Description</th>
                    <th class="py-4 text-[10px] font-black text-gray-900 uppercase tracking-widest text-center">Qté</th>
                    <th class="py-4 text-[10px] font-black text-gray-900 uppercase tracking-widest text-right">Prix Unitaire</th>
                    <th class="py-4 text-[10px] font-black text-gray-900 uppercase tracking-widest text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->repairOrder->parts as $part)
                <tr>
                    <td class="py-4">
                        <span class="text-sm font-black text-gray-900">{{ $part->part_name }}</span>
                        <p class="text-[10px] text-gray-500 uppercase mt-0.5">Pièce Détachée</p>
                    </td>
                    <td class="py-4 text-sm font-bold text-center text-gray-700">{{ $part->quantity }}</td>
                    <td class="py-4 text-sm font-bold text-right text-gray-700">{{ number_format($part->unit_price, 0, ',', ' ') }}</td>
                    <td class="py-4 text-sm font-black text-right text-gray-900">{{ number_format($part->total_price, 0, ',', ' ') }}</td>
                </tr>
                @endforeach

                @foreach($invoice->repairOrder->tasks as $task)
                <tr>
                    <td class="py-4">
                        <span class="text-sm font-black text-gray-900">{{ $task->title }}</span>
                        <p class="text-[10px] text-gray-500 uppercase mt-0.5">Main d'œuvre</p>
                    </td>
                    <td class="py-4 text-sm font-bold text-center text-gray-700">{{ $task->actual_time_minutes }}m</td>
                    <td class="py-4 text-sm font-bold text-right text-gray-700">-</td>
                    <td class="py-4 text-sm font-black text-right text-gray-900">-</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end">
            <div class="w-64 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="font-bold text-gray-500 uppercase">Sous-total</span>
                    <span class="font-black text-gray-900">{{ number_format($invoice->amount, 0, ',', ' ') }} F</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-bold text-gray-500 uppercase">TVA (19.25%)</span>
                    <span class="font-black text-gray-900">{{ number_format($invoice->tax, 0, ',', ' ') }} F</span>
                </div>
                <div class="h-px bg-gray-900"></div>
                <div class="flex justify-between text-lg">
                    <span class="font-black text-gray-900 uppercase">Total Net</span>
                    <span class="font-black text-blue-600">{{ number_format($invoice->total_payable, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-24 pt-12 border-t border-gray-100 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[3px] mb-2">Merci pour votre confiance</p>
            <p class="text-[9px] text-gray-400">PIISTON | Plateforme Automobile Mondiale | piiston.com</p>
        </div>
    </div>

    <!-- Print Control -->
    <div class="max-w-4xl mx-auto mt-8 flex justify-center no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl transition-all">
            Imprimer la Facture
        </button>
    </div>
</body>
</html>
