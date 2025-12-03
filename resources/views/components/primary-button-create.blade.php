<button {{ $attributes->merge(['type' => 'button', 'class' => 'flex flex-row items-center gap-2 bg-M1 text-M6 px-4 py-2 text-sm rounded-lg hover:bg-M3 transition-colors duration-300']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 256 256">
        <path d="M228,128a12,12,0,0,1-12,12H140v76a12,12,0,0,1-24,0V140H40a12,12,0,0,1,0-24h76V40a12,12,0,0,1,24,0v76h76A12,12,0,0,1,228,128Z"></path>
    </svg>
    {{ $slot }}
</button>