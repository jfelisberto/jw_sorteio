<!-- vertical Navbar menu -->
<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
  <nav class="navbar navbar-light navbar-expand-sm flex-row flex-nowrap">
    <ul class="nav flex-column nav-pills">

      <li>
        <div class="navbar-collapse collapse" id="navbarNavDropdown">
          <ul class="nav flex-sm-column flex-row nav-pills">

            <li class="nav-item">
                <a class="nav-link{{ $current == 'player' ? ' active' : '' }}" aria-current="page" href="{{ route('players') }}">
                <span data-feather="users"></span>
                Jogadores
              </a>
            </li>

            <li class="nav-item">
                <a class="nav-link{{ $current == 'match' ? ' active' : '' }}" aria-current="page" href="{{ route('matches') }}">
                <span data-feather="users"></span>
                Partidas
              </a>
            </li>
            {{-- Auth::user()->id --}}
            <li class="nav-item">
                <a class="nav-link{{ $current == 'automate' ? ' active' : '' }}" aria-current="page" href="{{ route('automations') }}">
                <span data-feather="users"></span>
                Automatizar
              </a>
            </li>

            <li class="nav-item">
                <a class="nav-link{{ $current == 'presence' ? ' active' : '' }}" aria-current="page" href="{{ route('presences') }}">
                <span data-feather="users"></span>
                Presenças
              </a>
            </li>

            <li class="nav-item">
                <a class="nav-link{{ $current == 'team' ? ' active' : '' }}" aria-current="page" href="{{ route('teams') }}">
                <span data-feather="users"></span>
                Times
              </a>
            </li>
        </div>
      </li>
    </ul>
  </nav>
</div>
