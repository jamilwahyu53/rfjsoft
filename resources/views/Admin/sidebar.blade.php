<!-- Desktop Sidebar -->
<nav class="d-none d-lg-block sidebar">
    <!-- HEADER PROFILE -->
    <div class="text-center py-4">
        <img 
            src="https://ui-avatars.com/api/?name={{ data_get($userLogin['data'], 'full_name', data_get($userLogin['data'], 'StaffName', 'Guest User')) }}&background=random&color=fff" 
            class="rounded-circle mb-2"
            width="80"
            height="80"
            alt="User Photo"
            style="object-fit: cover;"
        >
        <h6 class="mb-0"><strong>{{ data_get($userLogin['data'], 'full_name', data_get($userLogin['data'], 'StaffName', 'Guest User')) }}</strong></h6>
        <small class="text-muted">{{data_get($userLogin['data'], 'organization', data_get($userLogin['data'], 'Position', '')) }}</small>
        <hr class="mt-3 mb-0">
    </div>

    <div class="list-group list-group-flush mx-2 mt-4">
        @foreach(json_decode($sidebars) as $row)
        @if($userLogin['stRole'] == 'VISITOR' && (int) $row->Staff != 1 )
        <a href="{{ $row->Link }}" class="list-group-item list-group-item-action py-2
            {{ (request()->segment(1) == $row->Link) ? 'active' : '' }}">
            <i class="fas fa-chart-area fa-fw me-2"></i>
            <span>{{ $row->Menu }}</span>
        </a>
        @endif
        @if($userLogin['stRole'] == 'STAFF' && (int) $row->Staff == 1 )
        <a href="{{ $row->Link }}" class="list-group-item list-group-item-action py-2
            {{ (request()->segment(1) == $row->Link) ? 'active' : '' }}">
            <i class="fas fa-chart-area fa-fw me-2"></i>
            <span>{{ $row->Menu }}</span>
        </a>
        @endif
        @endforeach
        <a href="/Logout" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
        </a>
    </div>
</nav>

<!-- Mobile Offcanvas -->
<div class="d-lg-none d-flex align-items-center justify-content-between px-3 py-2 bg-light shadow-sm fixed-top" style="z-index: 1040;">
    <span class="fw-bold">
        @foreach(json_decode($sidebars) as $row)
            @if(request()->segment(1) == $row->Link)
                {{ $row->Menu }}
                @break
            @endif
        @endforeach
        @if(!request()->segment(1))
            Dashboard
        @endif
    </span> 
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        <i class="bi bi-list"></i>
    </button>
</div>


<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="list-group list-group-flush">
        @foreach(json_decode($sidebars) as $row)
        @if($userLogin['stRole'] == 'VISITOR' && (int) $row->Staff != 1 )
        <a href="{{ $row->Link }}" class="list-group-item list-group-item-action py-2
            {{ (request()->segment(1) == $row->Link) ? 'active' : '' }}">
            <i class="fas fa-chart-area fa-fw me-2"></i>
            <span>{{ $row->Menu }}</span>
        </a>
        @endif
        @if($userLogin['stRole'] == 'STAFF' && (int) $row->Staff = 1 )
        <a href="{{ $row->Link }}" class="list-group-item list-group-item-action py-2
            {{ (request()->segment(1) == $row->Link) ? 'active' : '' }}">
            <i class="fas fa-chart-area fa-fw me-2"></i>
            <span>{{ $row->Menu }}</span>
        </a>
        @endif
        @endforeach
        <a href="/Logout" class="list-group-item list-group-item-action py-2">
            <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
        </a>
    </div>
  </div>
</div>
