<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
      @endif
      <div class="card mt-3">
        <h1 class="text-warning pl-3">Buat menu baru!</h1>
          <form action="{{ route('store_menu') }}" method="POST">
            @csrf
              <div class="card-body">
                  <div class="mb-3">
                        <label for="">Nama<span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="">Harga</label>
                        <input type="number" name="harga" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Kategori</label>
                        <input type="text" name="kategori" class="form-control">
                    </div>
              </div>
              <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Buat</button>
              </div>
          </form>
        </div>
      </div>

      <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <h2>Daftar menu yang ada di kantin</h2>
                  <div class="btn-group mb-3" role="group">
                    <a href="{{ route('dashboard') }}" class="btn {{ !request('kategori') ? 'btn-primary' : 'btn-secondary' }}">Semua</a>
                    <a href="{{ route('dashboard', ['kategori' => 'makanan']) }}" class="btn {{ request('kategori') == 'makanan' ? 'btn-primary' : 'btn-secondary' }}">Makanan</a>
                    <a href="{{ route('dashboard', ['kategori' => 'minuman']) }}" class="btn {{ request('kategori') == 'minuman' ? 'btn-primary' : 'btn-secondary' }}">Minuman</a>
                  </div>
            </div>
            <div class="card-body">
                @foreach ($produk_kantin as $produk)
                    <div class="card mt-3">
                      <div class="card-body">
                          <div class="row">
                            <div class="col">
                                <h3>{{ $produk->nama }}</h3>
                                <h3>{{ $produk->harga }}</h3>
                                

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editButton{{ $produk->id }}">Edit</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteButton{{ $produk->id }}">Hapus</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#buyButton{{ $produk->id }}">Beli</button>
                                <!-- modal edit -->
                                <div class="modal fade" id="editButton{{ $produk->id }}" tabindex="-1" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit menu</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        <form action="{{ route('update_menu', $produk)}}"method="POST">
                                          @csrf
                                          @method('PUT')
                                          <div class="mb-3">
                                              <label for="">Nama<span class="text-danger">*</span></label>
                                              <input value="{{ $produk->nama }}" type="text" name="nama" class="form-control" required maxlength="50">
                                          </div>
                                          <div class="mb-3">
                                              <label for="">Harga</label>
                                              <input value="{{ $produk->harga }}"type="number" name="harga" class="form-control">
                                          </div>
                                          <div class="mb-3">
                                              <label for="">Kategori</label>
                                              <input value="{{ $produk->kategori }}"type="text" name="kategori" class="form-control">
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Edit</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                
                                <!-- modal delete -->
                                <div class="modal fade" id="deleteButton{{ $produk->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">HAPUS?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <form action="{{ route('delete_menu', $produk) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body">
                                          <h5 class="text-danger">Apakah kau yakin ingin menghapus menu {{ $produk->nama }}</h5>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                          <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center">
                              @if ($produk->kategori == 'makanan')
                              <span class="badge text-bg-success">{{ $produk->kategori }}</span>
                                 @else
                                  <span class="badge text-bg-primary">{{ $produk->kategori }}</span>
                                 @endif
                            </div>
                          </div>
                      </div>
                    </div>
                @endforeach
            </div>
        </div>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>