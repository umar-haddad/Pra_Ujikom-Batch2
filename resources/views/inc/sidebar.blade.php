<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      {{-- Semua level bisa lihat Dashboard --}}
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('dashboard.index') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      {{-- USER LEVEL 2 -> operator --}}
      @if(Auth::user()->id_level == 2)
          <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('trans.index') }}">
              <i class="bi bi-dash-circle"></i>
              <span>Transaksi</span>
            </a>
          </li><!-- End Transaksi Nav -->

      {{-- USER LEVEL 3 ->Leader --}}
      @elseif(Auth::user()->id_level == 3)
          <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('report.index') }}">
              <i class="bi bi-file-earmark-text"></i>
              <span>Laporan</span>
            </a>
          </li><!-- End Laporan Nav -->

      {{-- ADMIN (LEVEL 1) --}}
      @elseif(Auth::user()->id_level == 1)
          {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-button-wide"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="{{ route('customer.index') }}">
                  <i class="bi bi-circle"></i><span>Customer</span>
                </a>
              </li>
            </ul>
          </li><!-- End Components Nav -->

          <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('trans.index') }}">
              <i class="bi bi-dash-circle"></i>
              <span>Transaksi</span>
            </a>
          </li> --}}

          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-journal-text"></i><span>Hak Akses</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                <a href="{{ route('customer.index') }}">
                  <i class="bi bi-circle"></i><span>Customer</span>
                </a>
              </li>
              <li>
                <a href="{{ route('user.index') }}">
                  <i class="bi bi-circle"></i><span>User</span>
                </a>
              </li>
              <li>
                <a href="{{ route('level.index') }}">
                  <i class="bi bi-circle"></i><span>Level</span>
                </a>
              </li>
              <li>
                <a href="{{ route('service.index') }}">
                  <i class="bi bi-circle"></i><span>Service</span>
                </a>
              </li>
            </ul>
          </li><!-- End Forms Nav -->

          <li class="nav-heading">Pages</li>

          <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('error404') }}">
              <i class="bi bi-dash-circle"></i>
              <span>Error 404</span>
            </a>
          </li><!-- End Error 404 Page Nav -->
      @endif

    </ul>

</aside><!-- End Sidebar-->
