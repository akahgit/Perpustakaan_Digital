<div x-data="{ 
        open: false, 
        title: '', 
        message: '', 
        action: '', 
        method: 'POST',
        confirmText: 'Ya, Lanjutkan',
        cancelText: 'Batal',
        type: 'info', // info, danger, success, warning
        setup(data) {
            this.title = data.title || 'Konfirmasi Tindakan';
            this.message = data.message || 'Apakah Anda yakin ingin melanjutkan?';
            this.action = data.action || '';
            this.method = data.method || 'POST';
            this.confirmText = data.confirmText || 'Ya, Lanjutkan';
            this.cancelText = data.cancelText || 'Batal';
            this.type = data.type || 'info';
            this.open = true;
        }
    }"
    @confirm.window="setup($event.detail)"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[999] flex items-center justify-center p-4 transition-all duration-300"
    role="dialog"
    aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
         @click="open = false"></div>

    <!-- Modal Content -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative z-10 w-full max-w-sm bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in">
        
        <!-- Header Strip -->
        <div :class="{
            'bg-indigo-500': type === 'info',
            'bg-rose-500': type === 'danger',
            'bg-emerald-500': type === 'success',
            'bg-amber-500': type === 'warning'
        }" class="h-1.5 w-full"></div>

        <div class="p-6">
            <!-- Icon -->
            <div class="flex justify-center mb-5">
                <div :class="{
                    'bg-indigo-500/10 text-indigo-400 border-indigo-500/20': type === 'info',
                    'bg-rose-500/10 text-rose-400 border-rose-500/20': type === 'danger',
                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/20': type === 'success',
                    'bg-amber-500/10 text-amber-400 border-amber-500/20': type === 'warning'
                }" class="w-16 h-16 rounded-2xl border flex items-center justify-center shadow-lg">
                    <i :class="{
                        'fa-circle-info': type === 'info',
                        'fa-triangle-exclamation': type === 'danger',
                        'fa-circle-check': type === 'success',
                        'fa-circle-exclamation': type === 'warning'
                    }" class="fas text-2xl"></i>
                </div>
            </div>

            <!-- Text -->
            <h3 class="text-lg font-extrabold text-white text-center tracking-tight mb-2" x-text="title"></h3>
            <p class="text-sm text-slate-400 text-center leading-relaxed mb-6" x-html="message"></p>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="button" 
                        @click="open = false" 
                        class="flex-1 px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold rounded-xl hover:bg-white/10 transition"
                        x-text="cancelText">
                </button>
                
                <form id="global-confirm-form" :action="action" :method="method === 'GET' ? 'GET' : 'POST'" class="flex-1">
                    <template x-if="method !== 'POST' && method !== 'GET'">
                        <input type="hidden" name="_method" :value="method">
                    </template>
                    <template x-if="method !== 'GET'">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </template>
                    
                    <button type="submit" 
                            @click.prevent="$el.closest('form').submit()"
                            :class="{
                                'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-600/20': type === 'info',
                                'bg-rose-600 hover:bg-rose-500 shadow-rose-600/20': type === 'danger',
                                'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/20': type === 'success',
                                'bg-amber-600 hover:bg-amber-500 shadow-amber-600/20': type === 'warning'
                            }"
                            class="w-full px-4 py-2.5 text-white text-xs font-bold rounded-xl shadow-lg transition"
                            x-text="confirmText">
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
