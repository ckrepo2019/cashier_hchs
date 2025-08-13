@extends('layouts.app')

@section('content')
    <div class="" style="height: 100vh; ">
        <div class="row">
            <div class="col-md-12 mt-5">
                <h3>Essentiel | Cashier</h3>
            </div>
        </div>
        <form class="form-signin" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="row pt-5" style="">
                <div class="col-md-3">
                    &nbsp;
                </div>
                <div class="col-md-3 mt-5" style="border-right: solid 3px gray;">
                    <div class="form-group pl-4 mt-3">
                        <label for="email" class="sr-only">User Login</label>
                        <input id="email" type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="width: 100% !important" placeholder="User Login">   

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror 
                    </div>  
                    <div class="form-group pl-4">
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input id="password" type="password" class="form-control form-control-lg mt-1 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-group pl-4">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                    </div>

                                
                </div>
                <div class="col-md-3 mt-5">
                    {{-- <img class="mb-4 mt-3" src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="" width="172" height="172" style="margin-left: -157px"> --}}
                    @php
                        $schoolinfo = DB::table('schoolinfo')
                            ->first();
                    @endphp
                    <img class="mb-4 mt-3" src="{{$schoolinfo->essentiellink . '/' . $schoolinfo->picurl}}" alt="" width="172" height="172" style="margin-left: -157px">
                </div>  
            </div>
        </form>
    </div>

@endsection
