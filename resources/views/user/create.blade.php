@extends('app')
@section('content')

<div class="container"></div>
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="Post">
                        @csrf
                        <label for="">Nama :</label>
                        <input type="text" class="form-control" name="name" required>
                        <label for="">Email :</label>
                        <input type="email" class="form-control" placeholder="masukkan email..." name="email" required>
                        <label for="">Password :</label>
                        <input type="password" class="form-control" placeholder="masukkan password..." name="password" required>
                        <div class="mb-3">
                            <label for="id_level">pilih Jabatan</label>
                            <select name="id_level" id="id_level" class="form-control" required>
                                    <option value=""><-- pilih --></option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
