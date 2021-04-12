<x-layout>
  <h2>Le top d'animes</h2>
  @foreach($animestop as $element)

  <div class="ratingID">
   <a href="/anime/{{$element->id}}"><li>
     
   <p class="cta">{{ $element->rating }}/10</p>
     {{$element->title }}
     
     </li></a>
     <div class="commentaire">
     <p>{{$element->comment }}</p> 
     </div>
  </div>
  @endforeach
  <x-slot name="title">
  </x-slot>
  </x-layout>
  