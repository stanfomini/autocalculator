<x-mail::message>
# Introduction

<p>You have been added to the team. Please use the following temporary password to log in:</p>
<p><strong>{{ $password }}</strong></p>


<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
