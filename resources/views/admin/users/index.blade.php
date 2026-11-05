@component('layouts.admin', [
    'title' => 'Manajemen Pelanggan & Hak Akses',
    'pageTitle' => 'Pelanggan & Hak Akses'
])

<div x-data="{ showAddUser: false, editingUser: null }" class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-headline text-2xl font-extrabold text-navy tracking-tight">
                Manajemen Pelanggan &amp; Hak Akses
            </h1>
            <p class="text-sm text-muted mt-0.5">Pantau akun pelanggan, riwayat transaksi, penetapan hak akses Admin CMS, dan reset kata sandi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
            <!-- Form Cari Pengguna -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative flex-1 sm:w-60">
                <input type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari nama / email user..."
                       class="h-10 pl-9 pr-3.5 rounded-xl bg-white border border-line-strong text-sm font-label focus:border-navy focus:ring-2 focus:ring-emerald/20 w-full transition">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
            </form>
            <button type="button" @click="showAddUser = !showAddUser"
                    class="btn btn-primary h-10 px-4 rounded-xl font-label text-xs font-bold cursor-pointer shrink-0"
                    x-text="showAddUser ? 'Tutup Form' : '+ Tambah User / Admin'">
            </button>
        </div>
    </div>

    <!-- Form Tambah User Baru -->
    <form x-show="showAddUser" x-cloak action="{{ route('admin.users.store') }}" method="POST"
          class="card p-5 border-2 border-navy grid grid-cols-1 sm:grid-cols-4 gap-3 font-label text-xs">
        @csrf
        <div>
            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Nama Lengkap *</label>
            <input type="text" name="name" required class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition font-medium">
        </div>
        <div>
            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Alamat Email *</label>
            <input type="email" name="email" required class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition">
        </div>
        <div>
            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Password Awal *</label>
            <input type="password" name="password" required minlength="6" placeholder="Min. 6 karakter" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white focus:ring-2 focus:ring-emerald/20 transition">
        </div>
        <div>
            <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Role Hak Akses *</label>
            <div class="flex gap-2">
                <select name="role" class="flex-1 h-10 px-2.5 rounded-xl bg-surface-low border border-line focus:border-navy focus:bg-white text-xs font-bold">
                    <option value="customer">Customer (Member)</option>
                    <option value="admin">Super Admin CMS</option>
                </select>
                <button type="submit" class="btn btn-primary h-10 px-4 rounded-xl font-bold cursor-pointer shrink-0">
                    Simpan
                </button>
            </div>
        </div>
    </form>

    <!-- Tabel Pengguna Enterprise (Bersih & Rapi) -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm divide-y divide-line font-label">
                <thead class="bg-surface-low text-navy font-bold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Identitas Pengguna</th>
                        <th class="p-4">Peran / Hak Akses</th>
                        <th class="p-4">Riwayat Belanja</th>
                        <th class="p-4 text-right">Tindakan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-sm">
                    @forelse($users as $u)
                        <tr class="hover:bg-surface-low/50 transition">
                            <!-- User Identity -->
                            <td class="p-4 align-top">
                                <div class="flex items-start gap-3">
                                    @if($u->avatar)
                                        <img src="{{ $u->avatar }}" alt="{{ $u->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 mt-0.5 border border-line shadow-xs">
                                    @else
                                        <div class="w-10 h-10 rounded-full {{ $u->role === 'admin' ? 'bg-navy text-white' : 'bg-surface-low text-navy' }} font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 border border-line shadow-xs">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <strong class="text-navy font-bold text-sm">{{ $u->name }}</strong>
                                            @if($u->firebase_uid)
                                                <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold uppercase tracking-wider" title="Login Google Firebase Aktif">
                                                    Google
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-muted block text-xs mt-0.5">{{ $u->email }}</span>
                                        <span class="text-[11px] text-muted block mt-0.5">Bergabung: {{ $u->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Role Akses -->
                            <td class="p-4 align-top whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 {{ $u->role === 'admin' ? 'bg-emerald-soft/30 text-emerald border border-emerald/20' : 'bg-surface-low text-muted border border-line' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $u->role === 'admin' ? 'bg-emerald' : 'bg-muted' }}"></span>
                                    <span>{{ $u->role === 'admin' ? 'Super Admin CMS' : 'Customer (Member)' }}</span>
                                </span>
                            </td>

                            <!-- Transaction stats -->
                            <td class="p-4 align-top whitespace-nowrap">
                                <span class="font-bold text-navy block text-xs">{{ $u->orders_count }} Pesanan</span>
                                <span class="text-muted text-xs block mt-0.5">Total: Rp {{ number_format($u->orders_sum_total_amount ?? 0, 0, ',', '.') }}</span>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 align-top text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" 
                                            @click="editingUser = { id: {{ $u->id }}, name: @js($u->name), email: @js($u->email), role: @js($u->role) }"
                                            class="h-8 px-3 rounded-lg border border-navy text-navy hover:bg-navy hover:text-white font-bold text-xs transition cursor-pointer">
                                        Edit
                                    </button>

                                    @if($u->id !== Auth::id())
                                        <button type="button" 
                                                @click="openConfirm('Hapus Akun ' + @js($u->name), 'Akun pengguna ini akan dihapus dari basis data sistem.', '{{ route('admin.users.destroy', $u->id) }}', 'DELETE')" 
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition cursor-pointer"
                                                title="Hapus Akun">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-soft/30 text-emerald font-bold text-[11px]">
                                            Akun Anda
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-muted text-xs">
                                Tidak ada akun yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 bg-surface-low border-t border-line flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-label">
                <span class="text-muted">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari total {{ $users->total() }} akun
                </span>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Edit Akun Pengguna -->
    <div x-show="editingUser !== null" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-sm"
         @keydown.escape.window="editingUser = null">
        <div @click.outside="editingUser = null"
             class="bg-white rounded-3xl border border-line shadow-2xl max-w-md w-full p-6 space-y-4 font-label">
            
            <div class="flex items-center justify-between pb-3 border-b border-line">
                <div>
                    <h2 class="font-headline font-bold text-base text-navy">Edit Akun &amp; Role Pengguna</h2>
                    <p class="text-xs text-muted" x-text="editingUser?.email"></p>
                </div>
                <button type="button" @click="editingUser = null" class="text-muted hover:text-navy text-xl leading-none">&times;</button>
            </div>

            <template x-if="editingUser">
                <form :action="'{{ url('/admin/users') }}/' + editingUser.id" method="POST" class="space-y-3.5 text-xs">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Nama Lengkap *</label>
                        <input type="text" name="name" x-model="editingUser.name" required 
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-bold text-navy">
                    </div>

                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Alamat Email *</label>
                        <input type="email" name="email" x-model="editingUser.email" required 
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs">
                    </div>

                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Role Hak Akses *</label>
                        <select name="role" x-model="editingUser.role" class="w-full h-10 px-3 rounded-xl bg-surface-low border border-line focus:bg-white text-xs font-bold">
                            <option value="customer">Customer (Member)</option>
                            <option value="admin">Super Admin CMS</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold mb-1 uppercase tracking-wider text-muted">Reset Password Baru (Opsional)</label>
                        <input type="password" name="new_password" minlength="6" placeholder="Biarkan kosong jika tidak diubah" 
                               class="w-full h-10 px-3.5 rounded-xl bg-surface-low border border-line focus:bg-white text-xs">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-line">
                        <button type="button" @click="editingUser = null" class="h-10 px-4 rounded-xl border border-line font-semibold text-ink">Batal</button>
                        <button type="submit" class="btn btn-primary h-10 px-5 rounded-xl font-bold cursor-pointer">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>

@endcomponent
