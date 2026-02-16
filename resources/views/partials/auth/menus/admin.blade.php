<a href="{{ route('admin.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
          {{ request()->routeIs('admin.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-home class="w-5 h-5" />
    Dashboard
</a>

<a href="{{ route('admin.attendance.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.attendance.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-clock class="w-5 h-5" />
    Kelola Absensi
</a>

<a href="{{ route('admin.terms.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.terms.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-rectangle-stack class="w-5 h-5" />
    Tahun Ajaran
</a>

<a href="{{ route('admin.majors.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.majors.*') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-squares-2x2 class="w-5 h-5" />
    Jurusan
</a>

<a href="{{ route('admin.classrooms.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.classrooms.*') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-building-library class="w-5 h-5" />
    Kelas
</a>

<a href="{{ route('admin.students.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.students.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-users class="w-5 h-5" />
    Daftar Siswa
</a>

<a href="{{ route('admin.teachers.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.teachers.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-user-circle class="w-5 h-5" />
    Daftar Guru
</a>


<a href="{{ route('admin.staff.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.staff.*') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-user-group class="w-5 h-5" />
    Daftar Staff
</a>

<a href="{{ route('admin.subjects.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.subjects.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-book-open class="w-5 h-5" />
    Daftar Mata Pelajaran
</a>

<a href="{{ route('admin.rooms.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.rooms.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-building-office class="w-5 h-5" />
    Gedung dan Ruangan
</a>

<a href="{{ route('admin.rooms.history') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.rooms.history') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-document-text class="w-5 h-5" />
    Penggunaan Ruangan
</a>

<a href="{{ route('admin.schedules.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.schedules.*') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-clock class="w-5 h-5" />
    Kelola Jadwal
</a>

<a href="{{ route('admin.reports.index') }}"
    class="flex items-center gap-3 px-4 py-2 text-sm rounded-md hover:bg-slate-200 dark:hover:bg-slate-800
   {{ request()->routeIs('admin.reports.index') ? 'bg-slate-200 dark:bg-slate-800 font-semibold' : '' }}">
    <x-heroicon-o-document-chart-bar class="w-5 h-5" />
    Laporan Harian
</a>
