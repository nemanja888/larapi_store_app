HELLO {{$user->name}}

You changed you email, so you need to verify this new address. Please use the link below.

{{ route('verify', ['token' => $user->verification_token] ) }}

