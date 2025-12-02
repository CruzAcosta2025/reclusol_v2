<select {{ $attributes->merge(['class' => 'w-full px-4 py-2 border-1 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-60 focus:border-accent bg-white/80']) }}>
    {{ $slot }}
</select>
