<?php

use App\Models\Review;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ReviewModel;
use App\Models\watchlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReviewController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  $animes = DB::select("SELECT * FROM animes");
  return view('welcome', ["animes" => $animes]);
});

Route::get('/anime/{id}', function ($id) {
  $anime = DB::select("SELECT * FROM animes WHERE id = ?", [$id])[0];
  return view('anime', ["anime" => $anime]);
});

Route::get('/anime/{id}/new_review', function ($id) {
  $anime = DB::select("SELECT * FROM animes WHERE id = ?", [$id])[0];
  return view('new_review', ["anime" => $anime]);

});

Route::get('/login', function () {
  return view('login');
});

Route::post('/login', function (Request $request) {
  $validated = $request->validate([
    "username" => "required",
    "password" => "required",
  ]);
  if (Auth::attempt($validated)) {
    return redirect()->intended('/');
  }
  return back()->withErrors([
    'username' => 'The provided credentials do not match our records.',
  ]);
});

Route::get('/signup', function () {
  return view('signup');
});

Route::post('signup', function (Request $request) {
  $validated = $request->validate([
    "username" => "required",
    "password" => "required",
    "password_confirmation" => "required|same:password"
  ]);
  $user = new User();
  $user->username = $validated["username"];
  $user->password = Hash::make($validated["password"]);
  $user->save();
  Auth::login($user);

  return redirect('/');
});

Route::post('signout', function (Request $request) {
  Auth::logout();
  $request->session()->invalidate();
  $request->session()->regenerateToken();
  return redirect('/');
});



//creation d'une fuction qui permet d'ajouter des cretiques dans bdd
Route::post('anime/{id}/new_review',function (Request $request, $id) {

  if(Auth::user()){
    $validated = $request->validate([
      "number" => "required",
      "text" => "required",
      ]);
      $message = new ReviewModel;
      $message->rating = $validated["number"];
      $message->comment = $validated["text"];
      $message->user_id = Auth::id();
      $message->anime_id = $id;
      $message->save();
      
      return back()->with('success','Votre avis a été ajouté ');
    }else{
      return redirect('/login')->with('success','Pour ajouter une cretique, Connectez-vous');
    }     
});

//Creation une fuctione pour afficher le top des animes
Route::get('/top',function (){
    $top = DB::table('review_models')
    ->join('animes', 'review_models.anime_id','animes.id',)->orderBy('review_models.rating','desc')
    ->get();

// dd($top);
    return view('top',[
        'animestop'=>$top,]);
});

// Route pour mywatchlist et enregistrer dans bdd
Route::get('/watchlist',function (){

  if(Auth::user()){

    $idusers = Auth::id();

    $listregarder = DB::table('watchlists')
                ->join('animes', 'watchlists.anime_id', '=', 'animes.id')
                ->join('users', 'watchlists.user_id', '=', 'users.id')
                ->where('user_id', '=', $idusers)
                ->get();

    return view('watchlist', ["animes" => $listregarder]);
}else{
  
  return redirect('/login')->with('success','Pour ajouter un anime, Connectez-vous');
}
});
// Pour ajoute des animes dans son watchlist
Route::get('/anime/{id}/add_to_watch_list', function($id){
  
  $list = DB::table('watchlists')->where('anime_id', $id)->first();
  // dd($list);
  if(Auth::user()){
      if(!$list){

        $towatch = new watchlist;
        $towatch->user_id = Auth::id();
        $towatch->anime_id = $id;
        $towatch->save();
        
      }
    }
      return redirect('/watchlist')->with('success','Ajouté');



});

