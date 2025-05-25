<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        {{ config('app.name', 'SIAKAD') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Dashboard
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('users.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Manajemen User
                        </a>
                        <a href="{{ route('tahun-ajaran.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('tahun-ajaran.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Tahun Ajaran
                        </a>
                        <a href="{{ route('mata-kuliah.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('mata-kuliah.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Mata Kuliah
                        </a>
                        <a href="{{ route('kelas.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('kelas.index') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Kelas
                        </a>
                    @endif

                    @if(auth()->user()->isDosen())
                        <a href="{{ route('kelas.dosen') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('kelas.dosen') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Kelas Saya
                        </a>
                    @endif

                    @if(auth()->user()->isMahasiswa())
                        <a href="{{ route('kelas.mahasiswa') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('kelas.mahasiswa') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Kelas Saya
                        </a>
                        <a href="{{ route('kelas.nilai.saya') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('kelas.nilai.saya') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Nilai Saya
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav> 