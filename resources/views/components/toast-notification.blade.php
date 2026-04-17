{{-- 
    ==========================================================================
    GLOBAL TOAST NOTIFICATION SYSTEM (Laravel 13 + Alpine.js + Tailwind v4)
    ==========================================================================
--}}

<div x-data
     class="fixed top-5 right-5 z-[100] flex flex-col gap-4 pointer-events-none"
     style="width: 400px; max-width: calc(100vw - 2.5rem);">
    
    <template x-for="toast in $store.toast.list" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-12 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             class="pointer-events-auto relative overflow-hidden group">
            
            {{-- Main Toast Body --}}
            <div class="bg-slate-800/95 backdrop-blur-md border border-white/10 rounded-2xl shadow-2xl flex items-start p-4 gap-4 ring-1 ring-white/5"
                 :class="`border-l-4 ${$store.toast.getAccent(toast.type)}`">
                
                {{-- Dynamic Icon --}}
                <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-lg"
                     :class="$store.toast.getIconBg(toast.type)">
                    <i :class="$store.toast.getIcon(toast.type)"></i>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-white mb-0.5" x-text="$store.toast.getTitle(toast.type)"></h4>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium" x-text="toast.message"></p>
                </div>

                {{-- Close Button --}}
                <button @click="$store.toast.remove(toast.id)" class="text-slate-500 hover:text-white transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            {{-- Progress Bar (Animated) --}}
            <div class="absolute bottom-0 left-1 right-0 h-0.5 rounded-full"
                 :class="$store.toast.getProgressBarColor(toast.type)"
                 :style="`animation: toast-timer ${toast.duration}ms linear forwards`"
                 style="transform-origin: left;">
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes toast-timer {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('toast', {
            list: [],
            counter: 0,

            add(msg, type = 'success', duration = 4000) {
                const id = ++this.counter;
                this.list.push({ id, message: msg, type, duration, visible: true });
                setTimeout(() => this.remove(id), duration);
            },

            remove(id) {
                const index = this.list.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.list[index].visible = false;
                    setTimeout(() => {
                        this.list = this.list.filter(t => t.id !== id);
                    }, 300);
                }
            },

            // Helpers for styling
            getAccent(type) {
                return {
                    success: 'border-emerald-500',
                    error: 'border-rose-500',
                    warning: 'border-amber-500',
                    info: 'border-blue-500'
                }[type] || 'border-blue-500';
            },
            getIconBg(type) {
                return {
                    success: 'bg-emerald-500/20 text-emerald-400',
                    error: 'bg-rose-500/20 text-rose-400',
                    warning: 'bg-amber-500/20 text-amber-400',
                    info: 'bg-blue-500/20 text-blue-400'
                }[type] || 'bg-blue-500/20 text-blue-400';
            },
            getIcon(type) {
                return {
                    success: 'fas fa-check-circle',
                    error: 'fas fa-times-octagon',
                    warning: 'fas fa-exclamation-triangle',
                    info: 'fas fa-info-circle'
                }[type] || 'fas fa-bell';
            },
            getTitle(type) {
                return {
                    success: 'Sukses!',
                    error: 'Gagal',
                    warning: 'Perhatian',
                    info: 'Informasi'
                }[type] || 'Notifikasi';
            },
            getProgressBarColor(type) {
                return {
                    success: 'bg-emerald-500',
                    error: 'bg-rose-500',
                    warning: 'bg-amber-500',
                    info: 'bg-blue-500'
                }[type] || 'bg-blue-500';
            }
        });

        // Global shorthand
        window.toast = {
            success: (msg, d) => Alpine.store('toast').add(msg, 'success', d),
            error: (msg, d) => Alpine.store('toast').add(msg, 'error', d),
            warning: (msg, d) => Alpine.store('toast').add(msg, 'warning', d),
            info: (msg, d) => Alpine.store('toast').add(msg, 'info', d),
        };
    });

    // Auto-trigger from Laravel Sessions
    document.addEventListener('DOMContentLoaded', () => {
        // We use a small timeout to ensure Alpine is ready
        setTimeout(() => {
            @if(session('success')) window.toast.success(@json(session('success'))); @endif
            @if(session('error'))   window.toast.error(@json(session('error'))); @endif
            @if(session('warning')) window.toast.warning(@json(session('warning'))); @endif
            @if(session('info'))    window.toast.info(@json(session('info'))); @endif
            
            // Validation errors
            @if($errors->any())
                window.toast.error(@json(implode(' · ', $errors->all())));
            @endif
        }, 100);
    });
</script>
