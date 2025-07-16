@extends('app');
@section('content');

<div class="container"></div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="">{{ $title }}</h1>
                </div>
                <div class="card-body">
                    <div class="mb-3" align="right">
                        <a href="{{ route('user.create') }}" class="btn btn-success">tambah</a>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>email</th>
                                    <th>Jabatan</th>
                                    <th>aksi</th>
                                </tr>
                            </th>
                            @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td> {{ $data->name}} </td>
                                <td>{{( $data->email ) }}</td>
                                <td>{{ $data->level->name ?? '-'}}</td>
                                <td>
                                    <a href="{{ route('user.edit', $data->id) }}" class="btn btn-success">Edit</a>
                                    <form action="{{ route('user.destroy', $data->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('yakin mau hapus?')">
                                             Del
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
