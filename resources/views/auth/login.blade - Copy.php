@extends('layouts.app')

@section('content')
    <div class="row" style="height: 100vh">
        <div class="col-md-4">
            &nbsp;
        </div>
        <div class="col-md-4 my-auto">
            <form class="form-signin" method="POST" action="{{ route('login') }}">
                @csrf
              <img class="mb-4" src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="" width="172" height="172">
              <h1 class="h3 mb-3 font-weight-normal">Essentiel | Cashier</h1>
              <label for="email" class="sr-only">User Login</label>
              <input id="email" type="text" class="form-control form-control-lg {{-- @error('email') is-invalid @enderror --}}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="width: 100% !important" placeholder="User Login">
              <label for="inputPassword" class="sr-only">Password</label>
              <input id="password" type="password" class="form-control form-control-lg mt-1 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
              <div class="checkbox mb-3">
              
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
              
            </form>
        </div>
    </div>

@endsection
