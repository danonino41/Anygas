<form action="{{ route('logout') }}" method="POST" class="m-0">
    @csrf
    <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm fw-bold">
        <i class="bi bi-box-arrow-right me-1"></i> Salir del Sistema
    </button>
</form>