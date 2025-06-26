@php
    $hour = date('H');
    $greeting = $hour < 5 ? 'Malam'
              : ($hour < 12 ? 'Pagi'
              : ($hour < 15 ? 'Siang'
              : ($hour < 18 ? 'Sore' : 'Malam')));

    $user = Auth::user();
    $role = ucwords(str_replace('_', ' ', $user->roles()->first()->name));
@endphp

<div>
    <span class="text-slate-500 dark:text-slate-400 block text-sm">Hai, Selamat {{ $greeting }}</span>
    <span class="text-slate-900 dark:text-slate-200 block text-xs">{{ $user->name }} ({{ $role }})</span>
</div>
