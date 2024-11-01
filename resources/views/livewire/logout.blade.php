<div>
    <li>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item d-flex align-items-center">
                <i class="bi bi-door-open"></i>
                <span>Log Out</span>
            </button>
        </form>
    </li>
</div>
