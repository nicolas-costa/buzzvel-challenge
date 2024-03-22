<!DOCTYPE html>
<html>
<head>
    <title>{{$holiday->getTitle()}}</title>
</head>
<body style="text-align: center;">
<h2>{{$holiday->getTitle()}}</h2>

<p>Description: {{$holiday->getDescription()}}</p>

<p>Date: {{$holiday->getDate()}}</p>

<p>Locale: {{$holiday->getLocation()}}</p>

@if ($holiday->getParticipants())
    <h4>Participants:</h4>
@forelse($holiday->getParticipants() as $participant)
    <p>{{$participant}}</p>
@empty
    <p>No participants</p>
@endforelse
@endif

</body>
</html>
