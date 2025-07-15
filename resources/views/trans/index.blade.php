@extends('app');
@section('content');

<div class="container"></div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1>{{$title}}</h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <div class="mb-3" align="right">
                            <a href="{{ route('trans.create') }}" class="btn btn-success">tambah</a>
                        </div>
                        <tr>
                            <th>
                                <tr>
                                    <th>No</th>
                                    <th>No Pesanan</th>
                                    <th>Customer</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </th>
                            @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{ route('trans.show', $data->id) }}">{{ $data->order_code}} </a> </td>
                                <td>{{ $data->customer->name }}</td>
                                <td>{{ $data->order_end_date }}</td>
                                <td>{{ $data->status_text }}</td>
                                <td>
                                    <a href="{{ route('print_struk', $data->id) }}" target="_blank" class="btn btn-success">Print</a>
                                    <a href="{{route('trans.show', $data->id)}}" class="btn btn-success">Tampilkan</a>
                                    <form action="{{ route('trans.destroy', $data->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" data-confirm-delete="true">
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
