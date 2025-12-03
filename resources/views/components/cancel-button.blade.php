<button {{ $attributes->merge(['type' => 'button', 'class' => 'flex flex-row items-center gap-2 bg-gray-100 text-gray-800 border border-gray-300 px-4 py-2 text-sm rounded-lg hover:bg-gray-200 transition-colors duration-300']) }}>
    {{ $slot }}
</button>