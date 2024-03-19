{{-- @php
    $user = filament()
        ->auth()
        ->user();
    $roles = $user->roles->pluck('name');
    if ($roles->contains('RW') || $roles->contains('RT')) {
        $kelurahan = $user->kelurahan()->kel_nama;
        $rw = $user
            ->slsRoles()
            ->first()
            ->rw_groups()
            ->first()->rw_nama;
        $rt = $user
            ->slsRoles()
            ->first()
            ->rt_groups()
            ->first()->rt_nama;
    }
    
@endphp

<header>
    <span class="text-lg font-semibold sm:text-center dark:text-gray-400">
        @if ($roles->contains('super_admin'))
            Admin
        @endif
        @if ($roles->contains('RW'))
            Kelurahan {{ $kelurahan }} | {{ $rw }}
        @endif
        @if ($roles->contains('RT'))
            Kelurahan {{ $kelurahan }} | {{ $rw }} | {{ $rt }}
        @endif

    </span>
</header> --}}
