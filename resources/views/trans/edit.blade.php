@extends('app')
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">{{ $title }}</div>
                    <form action="{{ route('service.update', $service->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <label for="">Nama Service</label>
                        <input type="text" value="{{ $service->service_name }}" class="form-control" name="service_name" required>
                        <label for="">Harga</label>
                        <input type="Number" value="{{ $service->price }}" class="form-control" name="price" required>
                        <label for="">Deskripsi</label>
                        <textarea name="description" value="{{ $service->description }}" cols="30" rows="5" class="form-control"></textarea>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
