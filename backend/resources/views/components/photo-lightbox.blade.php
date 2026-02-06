@props(['photos' => []])

<div
    x-data="{
        open: false,
        current: 0,
        photos: @js($photos->map(fn($p) => [
            'url' => Storage::url($p->url),
            'caption' => $p->caption,
            'date' => $p->uploaded_at?->format('d/m/Y H:i'),
            'size' => $p->file_size ? number_format($p->file_size / 1024, 0) . ' KB' : null,
            'is_primary' => $p->is_primary,
        ])->values()),
        total: {{ $photos->count() }},

        openAt(index) {
            this.current = index;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        next() {
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
        },
    }"
    x-on:keydown.escape.window="close()"
    x-on:keydown.right.window="open && next()"
    x-on:keydown.left.window="open && prev()"
    @open-lightbox.window="openAt($event.detail.index)"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm"
        x-on:click.self="close()"
    >
        {{-- Close button --}}
        <button x-on:click="close()"
                class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Counter --}}
        <div class="absolute top-4 left-4 z-10">
            <span class="px-3 py-1.5 rounded-full bg-white/10 text-white text-sm font-medium backdrop-blur-sm"
                  x-text="(current + 1) + ' / ' + total"></span>
        </div>

        {{-- Prev button --}}
        <button x-show="total > 1"
                x-on:click.stop="prev()"
                class="absolute left-3 z-10 p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition group">
            <svg class="w-6 h-6 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>

        {{-- Next button --}}
        <button x-show="total > 1"
                x-on:click.stop="next()"
                class="absolute right-3 z-10 p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition group">
            <svg class="w-6 h-6 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        {{-- Image container --}}
        <div class="relative max-w-5xl max-h-[85vh] w-full mx-4 flex flex-col items-center">
            {{-- Main image --}}
            <template x-for="(photo, index) in photos" :key="index">
                <div x-show="current === index"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex flex-col items-center">
                    <img :src="photo.url"
                         :alt="photo.caption || 'Foto do ativo'"
                         class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl"
                         x-on:click.stop />

                    {{-- Caption bar --}}
                    <div class="mt-4 text-center" x-on:click.stop>
                        <p class="text-white text-sm font-medium" x-show="photo.caption" x-text="photo.caption"></p>
                        <div class="flex items-center justify-center gap-3 mt-1">
                            <span class="text-white/50 text-xs" x-text="photo.date"></span>
                            <span class="text-white/30" x-show="photo.size">&middot;</span>
                            <span class="text-white/50 text-xs" x-show="photo.size" x-text="photo.size"></span>
                            <span x-show="photo.is_primary"
                                  class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-indigo-500/30 text-indigo-300 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                </svg>
                                Principal
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Thumbnail strip --}}
        <div x-show="total > 1"
             class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10"
             x-on:click.stop>
            <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white/10 backdrop-blur-sm">
                <template x-for="(photo, index) in photos" :key="'thumb-' + index">
                    <button x-on:click="current = index"
                            :class="current === index ? 'ring-2 ring-white scale-110' : 'opacity-50 hover:opacity-80'"
                            class="w-12 h-12 rounded-lg overflow-hidden transition-all duration-200 shrink-0">
                        <img :src="photo.url"
                             :alt="'Miniatura ' + (index + 1)"
                             class="w-full h-full object-cover" />
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
