@extends('app')
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">{{ $title }}</div>
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <label for="">nama :</label>
                        <input type="text" value="{{ $user->name }}" class="form-control" name="name" required>
                        <label for="">Email :</label>
                        <input type="email" value="{{ $user->email }}" class="form-control" name="email" required>
                        <label for="">Password :</label>
                        <input type="password" class="form-control" name="password" required>
                        <div class="mb-3">
                            <label for="id_level">pilih Jabatan</label>
                            <select name="id_level" id="id_level" class="form-control" required>
                                    <option value=""><-- pilih --></option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>

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
