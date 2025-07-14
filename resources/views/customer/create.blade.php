@extends('app')
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">{{ $title }}</div>

                    <form action="{{ route('customer.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="">Nama Customer :</label>
                            <input type="text" placeholder="masukkan nama customer" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="">Nomor Handphone :</label>
                            <input type="number" placeholder="masukkan nomor telepon" class="form-control" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="">Alamat :</label>
                            <input type="address" placeholder="alamat" class="form-control" name="address" required>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
