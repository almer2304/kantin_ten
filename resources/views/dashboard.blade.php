<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu Kantin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <div class="container">
      <div class="card mt-5 shadow-sm">
        <div class="card-body">
          <h4 class="fw-bold mb-3">➕ Tambah Menu Baru</h4>
          <form action="{{ route('store_menu') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              <div class="col-md-4">
                <label for="nama" class="form-label">Nama Menu</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
              </div>
              <div class="col-md-2">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
              </div>
              <div class="col-md-2">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" required>
              </div>
              <div class="col-md-2">
                <label for="kategori" class="form-label">Kategori</label>
                <select id="kategori" name="kategori" class="form-select">
                  <option value="makanan">Makanan</option>
                  <option value="minuman">Minuman</option>
                </select>
              </div>
              <div class="col-md-2">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
              </div>
            </div>
            <button class="btn btn-primary mt-3">Simpan Menu</button>
          </form>
        </div>
      </div>
    </div>

    <div class="container py-5">
      <h1 class="text-center mb-4 fw-bold">🍱 Daftar Menu Kantin</h1>

      {{-- Pesan sukses/error --}}
      @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
      @endif

      {{-- Filter kategori --}}
      <form method="GET" action="{{ route('dashboard') }}" class="mb-4 text-center">
        <select name="kategori" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
          <option value="">Semua Kategori</option>
          <option value="makanan" {{ request('kategori') == 'makanan' ? 'selected' : '' }}>Makanan</option>
          <option value="minuman" {{ request('kategori') == 'minuman' ? 'selected' : '' }}>Minuman</option>
        </select>
      </form>

      {{-- Daftar produk --}}
      <div class="row">
        @forelse ($produk_kantin as $produk)
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
              {{-- Gambar produk --}}
              @if($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}" 
                     alt="{{ $produk->nama }}" 
                     class="card-img-top"
                     style="height: 180px; object-fit: cover;">
              @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                     style="height: 180px;">
                     <small>Tidak ada gambar</small>
                </div>
              @endif

              {{-- Isi kartu --}}
              <div class="card-body">
                <h5 class="card-title">{{ $produk->nama }}</h5>
                <p class="card-text mb-1 text-success fw-semibold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="mb-2">
                  Stok:
                  @if($produk->stok > 10)
                    <span class="badge bg-success">{{ $produk->stok }}</span>
                  @elseif($produk->stok > 0)
                    <span class="badge bg-warning text-dark">{{ $produk->stok }}</span>
                  @else
                    <span class="badge bg-danger">Habis</span>
                  @endif
                </p>

                {{-- Tombol aksi --}}
                <div class="d-flex justify-content-between align-items-center gap-1">
                  {{-- Tombol beli --}}
                  <form action="{{ route('beli_menu', $produk) }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="number" name="nominal" class="form-control form-control-sm mb-2" placeholder="Masukkan uang" required>
                    <button class="btn btn-success btn-sm w-100">Beli</button>
                  </form>

                  {{-- Tombol Edit --}}
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $produk->id }}">Edit</button>

                  {{-- Tombol Hapus --}}
                  <form action="{{ route('delete_menu', $produk) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')" class="flex-fill">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm w-100">Hapus</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          {{-- Modal Edit Produk --}}
          <div class="modal fade" id="editModal{{ $produk->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $produk->id }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                  <h5 class="modal-title" id="editModalLabel{{ $produk->id }}">Edit Menu: {{ $produk->nama }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('update_menu', $produk->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Nama Menu</label>
                      <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Harga</label>
                      <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Stok</label>
                      <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Kategori</label>
                      <select name="kategori" class="form-select">
                        <option value="makanan" {{ $produk->kategori == 'makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="minuman" {{ $produk->kategori == 'minuman' ? 'selected' : '' }}>Minuman</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Gambar (opsional)</label>
                      <input type="file" name="gambar" class="form-control">
                      @if($produk->gambar)
                        <small class="text-muted">Gambar saat ini:</small><br>
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="Gambar Lama" width="80" class="rounded mt-1">
                      @endif
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center text-muted mt-4">
            <p>Tidak ada menu kantin tersedia.</p>
          </div>
        @endforelse
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
