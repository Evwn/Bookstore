<!-- Scrollable Content -->
<div class="flex-1 overflow-y-auto p-6 md:p-8">
<div class="max-w-6xl mx-auto space-y-8">
<!-- Welcome Section -->
<div>
<h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Welcome back, Admin</h2>
<p class="text-slate-500 dark:text-slate-400">Manage your bookstore inventory, authors, and system settings from this dashboard.</p>
</div>
<!-- Danger Zone Card (System Reset) -->
<div class="bg-surface-light dark:bg-surface-dark rounded-xl shadow-sm border border-red-100 dark:border-red-900/30 overflow-hidden">
<div class="bg-red-50 dark:bg-red-900/10 px-6 py-4 border-b border-red-100 dark:border-red-900/30 flex items-center gap-3">
<span class="material-icons text-red-600 dark:text-red-400">warning</span>
<h3 class="font-bold text-red-800 dark:text-red-200">System Reset</h3>
</div>
<div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
<div class="max-w-2xl">
<p class="text-slate-600 dark:text-slate-300 mb-2">
                                This action will completely wipe the database and restore it to the default initial state. 
                            </p>
<p class="text-sm text-slate-500 dark:text-slate-400">
                                Warning: All custom authors, books, and sales records will be permanently lost. This action cannot be undone.
                            </p>
</div>
<button class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium shadow-sm hover:shadow-md transition-all flex items-center gap-2 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-background-dark">
<span class="material-icons text-sm">delete_forever</span>
                            Clear System
                        </button>
</div>
</div>
<!-- Quick Actions Grid -->
<div>
<h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
<span class="material-icons text-primary text-xl">bolt</span>
                        Quick Actions
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Add Author -->
<a class="group bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark hover:border-primary/50 dark:hover:border-primary/50 hover:shadow-md transition-all duration-200 flex flex-col items-center text-center relative overflow-hidden" href="#">
<div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
<div class="h-14 w-14 rounded-full bg-blue-50 dark:bg-blue-900/20 text-primary dark:text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
<span class="material-icons text-3xl">person_add</span>
</div>
<h4 class="font-bold text-slate-900 dark:text-white mb-1">Add Author</h4>
<p class="text-sm text-slate-500 dark:text-slate-400">Register a new author to the database</p>
</a>
<!-- Add Book -->
<a class="group bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark hover:border-primary/50 dark:hover:border-primary/50 hover:shadow-md transition-all duration-200 flex flex-col items-center text-center relative overflow-hidden" href="#">
<div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
<div class="h-14 w-14 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
<span class="material-icons text-3xl">library_add</span>
</div>
<h4 class="font-bold text-slate-900 dark:text-white mb-1">Add Book</h4>
<p class="text-sm text-slate-500 dark:text-slate-400">Create a new book entry in inventory</p>
</a>
<!-- Search Inventory -->
<a class="group bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark hover:border-primary/50 dark:hover:border-primary/50 hover:shadow-md transition-all duration-200 flex flex-col items-center text-center relative overflow-hidden" href="#">
<div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
<div class="h-14 w-14 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
<span class="material-icons text-3xl">search</span>
</div>
<h4 class="font-bold text-slate-900 dark:text-white mb-1">Search Inventory</h4>
<p class="text-sm text-slate-500 dark:text-slate-400">Find books by title, ISBN, or category</p>
</a>
</div>
</div>
<!-- Stats Overview (Optional Visual Filler) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
<div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
<div class="flex items-center justify-between mb-4">
<h4 class="font-bold text-slate-900 dark:text-white">Recent Activity</h4>
<span class="text-xs text-slate-400">Last 24h</span>
</div>
<div class="space-y-4">
<div class="flex items-center gap-3">
<div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
<span class="material-icons text-sm">edit</span>
</div>
<div class="flex-1">
<p class="text-sm font-medium text-slate-800 dark:text-slate-200">Updated "The Great Gatsby"</p>
<p class="text-xs text-slate-500">2 mins ago</p>
</div>
</div>
<div class="flex items-center gap-3">
<div class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
<span class="material-icons text-sm">add</span>
</div>
<div class="flex-1">
<p class="text-sm font-medium text-slate-800 dark:text-slate-200">Added Author "J.K. Rowling"</p>
<p class="text-xs text-slate-500">1 hour ago</p>
</div>
</div>
</div>
</div>
<div class="bg-gradient-to-br from-primary to-blue-700 rounded-xl p-6 text-white relative overflow-hidden">
<!-- Abstract decorative pattern -->
<div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl"></div>
<div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl"></div>
<h4 class="font-bold text-lg mb-1 relative z-10">System Status</h4>
<p class="text-blue-100 text-sm mb-6 relative z-10">Database connection is active.</p>
<div class="flex gap-4 relative z-10">
<div>
<p class="text-3xl font-bold">1,248</p>
<p class="text-xs text-blue-200 uppercase tracking-wide">Books</p>
</div>
<div class="w-px bg-white/20 h-10"></div>
<div>
<p class="text-3xl font-bold">482</p>
<p class="text-xs text-blue-200 uppercase tracking-wide">Authors</p>
</div>
</div>
</div>
</div>
<!-- Footer / Source Code -->
<div class="pt-8 pb-4 flex justify-center">
<button class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors text-sm font-medium">
<span class="material-icons text-sm">code</span>
                        Display Source
                    </button>
</div>
</div>
</div>