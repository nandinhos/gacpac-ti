<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-fab-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-fab-blue-hover focus:bg-fab-blue-hover active:bg-fab-blue-hover focus:outline-none focus:ring-2 focus:ring-fab-blue focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
