@extends('app')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Data Level</h4>
            </div>
            <div class="card-body">
                <div class="mb-3" align="right">
                    <a class="btn btn-primary" href="{{ route('level.create') }}">tambah</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($datas as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->name }}</td>
                        <td>
                            <a href="{{ route('level.edit', $data->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('level.destroy', $data->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('yakin mau hapus?')">
                                 Del
                            </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
