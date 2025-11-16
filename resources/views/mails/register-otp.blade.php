@section('content')
    Hi {{ $user->name }},<br>

    Ini adalah OTP Register anda: {{ $otp }}
@endsection