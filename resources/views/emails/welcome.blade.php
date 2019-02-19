HELLO {{$user->name}}

Thank you to create account. Please to verify your email usig the link.

{{ route('verify', ['token' => $user->verification_token] ) }}

