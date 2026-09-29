<div class="flex flex-col gap-4 pb-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <div class="size-2.5 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)]"></div>
                <h2 class="text-lg heading-premium">Supervision</h2>
            </div>
            <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest pl-4">Piiston Central Controller • {{ now()->format('d M Y') }}</p>
        </div>
        <flux:button href="{{ route('admin.settings') }}" size="xs" class="btn-premium-secondary">
            <i class="hgi-stroke hgi-settings-02 mr-2"></i> Settings
        </flux:button>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-dashboard.stat-card title="Users" :value="$totalUsers" icon="user-group" trend="+12%" color="#3B82F6" />
        <x-dashboard.stat-card title="Garages" :value="$totalGarages" icon="building-03" trend="+5%" color="#10B981" />
        <x-dashboard.stat-card title="Vehicle Fleet" :value="$totalVehicles" icon="truck" trend="+8%" color="#8B5CF6" />
        <x-dashboard.stat-card title="Global Revenue" :value="number_format($totalRevenue, 0, ',', ' ') . ' F'" icon="banknotes" trend="+15%" color="#F59E0B" />
    </div>

    <div class="grid grid-cols-12 gap-4">
        <!-- Content Area -->
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-4">

            <!-- Audit Logs Container -->
            <div class="card-premium !p-4 relative overflow-hidden">
                <div class="flex items-center justify-between mb-4 px-1">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-sm font-black text-zinc-900 dark:text-white uppercase tracking-widest">System Log</h3>
                        <p class="text-[10px] text-zinc-400 font-bold uppercase">Recent administrative activity</p>
                    </div>
                    <a href="{{ route('admin.audit-logs') }}" class="px-4 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-[10px] font-black uppercase text-blue-600 dark:text-blue-400 hover:scale-[1.02] transition-all">History</a>
                </div>

                <div class="space-y-2">
                    @forelse($recentAuditLogs as $log)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-zinc-50/50 dark:bg-white/[0.01] hover:bg-zinc-100 dark:hover:bg-white/[0.03] transition-all border border-transparent hover:border-zinc-200 dark:hover:border-white/10 group">
                            <div class="flex-shrink-0">
                                <div class="size-9 rounded-xl bg-white dark:bg-zinc-800 shadow-sm flex items-center justify-center border border-zinc-100 dark:border-white/5 group-hover:scale-110 transition-transform">
                                    <i class="hgi-stroke hgi-file-script text-blue-600 dark:text-blue-400 text-lg"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-black text-zinc-900 dark:text-white uppercase tracking-tight">{{ $log->action }}</p>
                                    <span class="text-[10px] font-bold text-zinc-400 uppercase">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-lg bg-zinc-100 dark:bg-white/5 text-[9px] font-black uppercase text-zinc-600 dark:text-zinc-400">{{ $log->entity_type }}</span>
                                    @if($log->administrator?->user)
                                        <span class="text-zinc-300 dark:text-zinc-700">•</span>
                                        <span class="text-[10px] text-blue-600 font-bold uppercase">{{ $log->administrator->user->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center label-premium">No activity</div>
                    @endforelse
                </div>
                <!-- Background decoration -->
                <div class="absolute -right-20 -top-20 size-64 bg-blue-500/5 rounded-full blur-[100px]"></div>
            </div>
        </div>

        <!-- Sidebar Monitoring -->
        <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">

            <!-- Platform Uptime & Health -->
            <div class="bg-zinc-900 dark:bg-zinc-800 p-4 rounded-2xl text-white shadow-xl border border-white/5 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex flex-col gap-1">
                            <h5 class="text-[10px] font-black uppercase tracking-[4px] text-white/30">SERVER HEALTH</h5>
                            <span class="text-xs font-black uppercase text-white">Monitoring Global</span>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 text-green-400 border border-green-500/20 shadow-[0_0_20px_rgba(34,197,94,0.15)]">
                            <div class="size-2 rounded-full bg-green-400 animate-pulse"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest">Live</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach(['CPU Load' => '24%', 'RAM Memory' => '52%', 'Network I/O' => '35%'] as $label => $val)
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between text-[11px] font-black uppercase text-white/60 tracking-widest">
                                <span>{{ $label }}</span>
                                <span class="text-white">{{ $val }}</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden p-0.5 border border-white/5">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" style="width: {{ $val }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/10 grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-black text-white/30 uppercase">Latence</span>
                            <span class="text-lg font-black tracking-tighter">142ms</span>
                        </div>
                        <div class="flex flex-col gap-1 text-right">
                            <span class="text-[10px] font-black text-white/30 uppercase">Uptime</span>
                            <span class="text-lg font-black tracking-tighter text-green-400">99.9%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Quick Grid -->
            <div class="grid grid-cols-1 gap-4">
                <x-dashboard.stat-card title="Modération" :value="$activeModerationCases" icon="flag-01" trend="Urgent" :isNegative="true" color="#EF4444" />
                <x-dashboard.stat-card title="Signalements" :value="$pendingReports" icon="alert-02" trend="Attention" :isNegative="true" color="#F59E0B" />
            </div>
        </div>
    </div>
</div>
