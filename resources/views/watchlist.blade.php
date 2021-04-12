<x-layout>
    <x-slot name="title">
    </x-slot>
        <h3>My watchlist of {{ Auth::user()->username }}</h3>
        @if(session('success'))
            <h3 id="success">
        {{session('success')}}
            </h3>
        @endif  
        @foreach($animes as $element) 
        <ul>
        <li><a href="/anime/{{$element->id}}">{{$element->title }}</li></a></ul> 
        @endforeach
</x-layout>
  