@extends('layouts.app')

@section('content')
<style>
    body {
        background-color:black;
    }
    .navbar {
        display:none;
    }
</style>
<div class="container mx-auto">
    <div class="flex justify-center items-center" style="height:100vh;">
        <div class="col-md-8 w-[330px]">
            <div id="game-title" class=" w-full text-center">
                <p style="font-family:'BloodThirst'; filter: drop-shadow(0 1px 1px rgb(2 2 2 / 0.95));" class="text-5xl text-red-700">DON'T DIE </p>
                <p style="filter: drop-shadow(0 1px 1px rgb(2 2 2 / 0.45));" class="text-xl italic text-red-700">or do, idk...</p>
            </div>
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('loginOrRegister') }}">
                        @csrf

                        <!-- Username -->
                        <div class="row mb-3 flex flex-col items-center">
                            <label for="username" class="col-md-4 col-form-label text-md-end text-white">{{ __('Username') }}</label>

                            <div class="col-md-6 p-1" style=" border-radius:10px; border:solid 2px #ccc;  background-color:#333">
                                <input style="background-color:#333; color:#ccc" id="username" type="text" class="text-center form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row mb-3 flex flex-col items-center">
                            <label for="password" class="col-md-4 col-form-label text-md-end text-white">{{ __('Password') }}</label>

                            <div class="col-md-6 p-1" style=" border-radius:10px; border:solid 2px #ccc;  background-color:#333">
                                <input style="background-color:#333; color:#ccc" id="password" type="password" class="text-center form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 flex justify-center">
                                <button type="submit" class="btn btn-primary w-[189px] h-[36px] bg-red-900 hover:bg-red-950 relative" name="action" value="login" onclick="toggleFullScreen()" style="border:solid 1px #ccc; border-radius:10px;">
                                    <p class="absolute text-red-700 text-3xl" style="font-family:'BloodThirst'; top:12px; left:50%; transform:translateX(-50%)">{{ __('PLAY') }}</p>
                                </button>
                                {{-- <button type="submit" class="btn btn-secondary" name="action" value="register" onclick="toggleFullScreen()">
                                    {{ __('Register') }}
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>
