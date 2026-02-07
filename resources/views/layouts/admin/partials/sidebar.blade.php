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

         <li class="menu-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
             <a href="{{ route('admin.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-home-circle"></i>
                 <div>Dashboard</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
             <a href="{{ route('admin.authors.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Authors</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
             <a href="{{ route('admin.categories.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Categories</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
             <a href="{{ route('admin.books.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Books</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.issued-books.*') ? 'active' : '' }}">
             <a href="{{ route('admin.issued-books.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Issued Book</div>
             </a>
         </li>

         {{-- <li class="menu-item {{ request()->routeIs('admin.fines.*') ? 'active' : '' }}">
             <a href="{{ route('admin.fines.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Fines</div>
             </a>
         </li> --}}


         <li class="menu-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
             <a href="{{ route('admin.members.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Members</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.librarians.*') ? 'active' : '' }}">
             <a href="{{ route('admin.librarians.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Librarians</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
             <a href="{{ route('admin.cms.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>CMS</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
             <a href="{{ route('admin.gallery.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Galleries</div>
             </a>
         </li>

          <li class="menu-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
             <a href="{{ route('admin.inquiries.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>Inquiries</div>
             </a>
         </li>

         <li class="menu-item {{ request()->routeIs('admin.news-letters.*') ? 'active' : '' }}">
             <a href="{{ route('admin.news-letters.index') }}" class="menu-link">
                 <i class="menu-icon tf-icons bx bx-save"></i>
                 <div>News Letters</div>
             </a>
         </li>


     </ul>
 </aside>
