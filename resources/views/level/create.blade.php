@extends('app')
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">{{ $title }}</div>

                    <form action="{{ route('level.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="">Nama Level :</label>
                            <input type="text" placeholder="masukkan nama level" class="form-control" name="name" required>
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
