 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
     <div class="app-brand demo">

         <a href="#" class="app-brand-link">
             <span class="app-brand-logo demo">
                 <img src="{{ asset('assets/img/logo.png') }}" alt="Library Logo" style="max-height: 40px;">
             </span>
             <span class="app-brand-text demo menu-text fw-bolder ms-2 text-uppercase">LMS</span>
         </a>

         <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
             <i class="bx bx-chevron-left bx-sm align-middle"></i>
         </a>
     </div>

     <div class="menu-inner-shadow"></div>
     <ul class="menu-inner py-1">

         <li class="menu-item {{ request()->routeIs('librarian.index') ? 'active' : '' }}">
             <a href="{{ route('librarian.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-home-circle"></i>
                 <div>Dashboard</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('librarian.books.*') ? 'active' : '' }}">
             <a href="{{ route('librarian.books.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-home-circle"></i>
                 <div>Books</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('librarian.issue-books.*') ? 'active' : '' }}">
             <a href="{{ route('librarian.issue-books.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-home-circle"></i>
                 <div>Issue Books</div>
             </a>
         </li>

     </ul>
 </aside>
