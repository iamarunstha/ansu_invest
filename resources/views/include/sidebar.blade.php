    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin-home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Ansu Invest <sup>Beta</sup></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin-home') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-company-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Company</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-news-list-get', ['news_type' => 'news']) }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>News</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-notice-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Notice</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-market-videos-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Market Videos</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-recommendations-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Recommendations</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-experts-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Experts</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-fiscal-year-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Fiscal Year</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-balance-sheet-sector-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Sectors</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-relative-valuation-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Absolute Valuation</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-proposed-dividend-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Proposed Dividend</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-agm-sgm-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>AGM SGM</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-investment-tabs-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Investment</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-stock-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Stocks</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-ownership-tabs-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Ownership</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-executives-list-all-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Executives</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-trailing-returns-upload-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Trailing Returns</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('upload-market-summary-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Market Summary Index</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('upload-nepse-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Nepse Index</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-static-list-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Definition & Ratios</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('suggested-reads-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Suggested Reads</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-footer-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Footer</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin-subscripton-plans-get') }}">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Subscriptions</span></a>
      </li>           
      <!-- Divider -->
      <hr class="sidebar-divider">
      <!-- Heading -->
    </ul>
    <!-- End of Sidebar -->
