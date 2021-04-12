<x-layout>
  <x-slot name="title">
    Nouvelle critique de {{ $anime->title }}
  </x-slot>
    <!-- Si on a ajouté un commainter, on doit l'indiquer -->
    @if(session('success'))
    <h3 id="success">
    {{session('success')}}
    </h3>
    @endif

  <h1>Nouvelle Critique de {{ $anime->title }}</h1>

  <div class="review">
  <div>
        <img alt="" src="/covers/{{ $anime->cover }}" />
      </div>
    <form  method="POST">
    @csrf
    <label for="number">Notez le rating</label>
    <input type="number"  class="cta" id="number" min ="0" max = "10" name="number" >
    <label for="text">Ajouter votre avis</label>
    <input type="text"  name="text" class="cta">
    <button class="cta">Ajouter</button>
    </form>
  </div>

</x-layout>
