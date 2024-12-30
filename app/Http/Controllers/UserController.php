<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function memberlist(Request $request)
    {

        $roles = DB::table('roles')
        ->select(['roles.id','roles.role_name'])
        ->get();
        $users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')

        ->where('users.role_id','LIKE','%'.($request->role_id).'%')
        ->where('users.name','LIKE','%'.($request->user_name).'%')
        ->paginate(30);
        $login_user = User::findOrFail(Auth::id());

                // dd($companies,$areas,$shops);

        return view('user.index',compact('roles','users','login_user'));
        // dd($roles,$areas,$users);
    }

    public function show($id)
    {
        $login_user = User::findOrFail(Auth::id());
        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info')
        ->first();


        // dd($login_user,$user,$user->id);
        return view('user.member_detail',compact('login_user','user',));

    }

    public function edit($id)
    {
        $login_user=Auth::id();
        $user = DB::table('users')
        ->join('areas','areas.id','=','users.area_id')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.name_kana','users.email','users.role_id','roles.role_name','users.area_id','areas.area_name','users.user_info','users.photo1','users.photo2',)
        ->first();
        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();
        // dd($companies,$areas,$shops);

        return view('user.member_edit',compact('login_user','user','areas'));
        // dd($login_user,$user);
    }

    public function member_update_rs11(Request $request, $id)
    {
        $user=User::findOrFail($id);

        $login_user = User::findOrFail(Auth::id());

        if(!is_null($request->file('photo1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('photo1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('photo1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/user/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = $user->photo1;
        };

        if(!is_null($request->file('photo2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('photo2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('photo2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/user/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = $user->photo2;
        };

        $user->name = $request->name;
        $user->name_kana = $request->name_kana;
        $user->email = $request->email;
        // $user->password = $request->password;
        $user->user_info = $request->user_info;
        $user->area_id = $request->area_id;
        $user->photo1 = $fileNameToStore1;
        $user->photo2 = $fileNameToStore2;



        // dd($request->name,$request->name_kana,$request->realname,$request->realname_kana,$request->user_info,);

        $user->save();

        return to_route('ac_info')->with(['message'=>'アカウント情報が更新されました','status'=>'info']);
    }

    public function member_update_rs1(Request $request, $id)
    {
        $user=User::findOrFail($id);

        $login_user = User::findOrFail(Auth::id());

        // dd($user->photo1,$request->photo2);

        $filrPath1 = 'public/user/' . $user->photo1;
        if(!empty($request->photo1) && (Storage::exists($filrPath1))){
            Storage::delete($filrPath1);
            // dd($filrPath1,$request->photo1);
        }
        $filrPath2 = 'public/user/' . $user->photo2;
        if((!empty($request->photo2))&&(Storage::exists($filrPath2))){
            Storage::delete($filrPath2);
        }

        if(!is_null($request->file('photo1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('photo1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('photo1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/user/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = $user->photo1;
        };

        if(!is_null($request->file('photo2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('photo2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('photo2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/user/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = $user->photo2;
        };

        $user->name = $request->name;
        $user->name_kana = $request->name_kana;
        $user->email = $request->email;
        // $user->password = $request->password;
        $user->user_info = $request->user_info;
        $user->area_id = $request->area_id;
        $user->photo1 = $fileNameToStore1;
        $user->photo2 = $fileNameToStore2;



        // dd($request->name,$request->name_kana,$request->realname,$request->realname_kana,$request->user_info,);

        $user->save();

        return to_route('ac_info')->with(['message'=>'アカウント情報が更新されました','status'=>'info']);
    }


    public function user_destroy($id)
    {
        $user = User::findorfail($id);
        $filrPath1 = 'public/user/' . $user->photo1;
        if(Storage::exists($filrPath1)){
            Storage::delete($filrPath1);
        }
        $filrPath2 = 'public/user/' . $user->photo2;
        if(Storage::exists($filrPath2)){
            Storage::delete($filrPath2);
        }

        User::findOrFail($id)->delete();

        return to_route('memberlist')->with(['message'=>'メンバーアカウントが削除されました','status'=>'alert']);
    }

    public function role_list(Request $request)
    {

        $roles = DB::table('roles')
        ->select(['roles.id','roles.role_name'])
        ->get();

        $users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')
        ->where('users.role_id','LIKE','%'.($request->role_id).'%')
        ->where('users.name','LIKE','%'.($request->user_name).'%')
        ->paginate(50);

        $login_user=User::findOrFail(Auth::id());

        $changeable_roles = DB::table('roles')
        ->where('roles.id','>=',$login_user->role_id)
        ->select('roles.id','roles.role_name')
        ->get();

        $changeable_users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.role_id','>=',$login_user->role_id)
        ->where('users.role_id','LIKE','%'.($request->role_id).'%')
        ->where('users.name','LIKE','%'.($request->user_name).'%')
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')
        ->get();
        // dd($login_user,$changeable_users,$changeable_roles);

        return view('role.role_list',compact('roles','users','changeable_users','changeable_roles'));
        // dd($roles,$areas,$users);
    }

    public function role_edit($id)
    {
        $login_user=Auth::id();
        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info')
        ->first();

        $roles = DB::table('roles')
        ->select(['roles.id','roles.role_name'])
        ->get();

        $login_user2=User::findOrFail(Auth::id());

        $changeable_roles = DB::table('roles')
        ->where('roles.id','>=',$login_user2->role_id)
        ->groupBy('roles.id','role_name')
        ->select('roles.id','roles.role_name')
        ->get();

        $changeable_users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.role_id','>=',$login_user2->role_id)
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')
        ->get();
        // dd($login_user,$changeable_users,$changeable_roles);

        return view('role.role_edit',compact('login_user','user','roles','changeable_users','changeable_roles'));
        // dd($login_user,$user);
    }

    public function role_update(Request $request, $id)
    {
        $user=User::findOrFail($id);

        $login_user = User::findOrFail(Auth::id());

        $user->role_id = $request->role_id;

        // dd($request->name,$request->name_kana,$request->realname,$request->realname_kana,$request->user_info,);

        $user->save();

        return to_route('role_list')->with(['message'=>'Role情報が更新されました','status'=>'info']);
    }

    public function ac_info()
    {

        $user=User::findOrFail(Auth::id());

        $user2 = DB::table('users')
        ->join('areas','areas.id','=','users.area_id')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$user->id)
        ->select('users.id','users.name','users.name_kana','users.email','users.role_id','roles.role_name','users.area_id','areas.area_name','users.user_info','users.photo1','users.photo2',)
        ->first();

        $favorites = DB::table('favorites')
        ->join('circuits','circuits.id','=','favorites.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->where('favorites.user_id',Auth::id())
        ->paginate(20);

        return view('user.ac_info',compact('user','user2','favorites'));
        // dd($user2);
    }

    public function ac_info_edit($id)
    {
        $login_user=User::findOrFail(Auth::id());

        $user = DB::table('users')
        ->join('areas','areas.id','=','users.area_id')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.name_kana','users.email','users.role_id','roles.role_name','users.area_id','areas.area_name','users.user_info','users.photo1','users.photo2',)
        ->first();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();
        // dd($companies,$areas,$shops);

        return view('user.ac_info_edit',compact('login_user','user','areas'));
    }

    public function pw_change($id)
    {
        $login_user=User::findOrFail(Auth::id());

        $user = DB::table('users')
        ->join('areas','areas.id','=','users.area_id')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.name_kana','users.email','users.role_id','roles.role_name','users.area_id','areas.area_name','users.user_info','users.photo1','users.photo2',)
        ->first();


        // dd($companies,$areas,$shops);

        return view('user.pw_change',compact('login_user','user'));
    }

    public function pw_update(Request $request)
    {

        $user=User::findOrFail(Auth::id());

        $request->validate([
            'current-password' => 'required',
            // 'new-password' => ['required', 'confirmed', Rules\Password::defaults()],
            'new-password' => ['required', Rules\Password::defaults()],
            'password-confirmation' => ['required', Rules\Password::defaults()],
        ]);

        if(!(Hash::check($request->get('current-password'), $user->password))) {
            return redirect()->route('pw_change',['user'=>$user->id])->withInput()->withErrors(array('current-password' => '現在のパスワードが間違っています'));
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            return redirect()->route('pw_change',['user'=>$user->id])->withInput()->withErrors(array('new-password' => '新しいパスワードが現在のパスワードと同じです'));
        }

        if(!strcmp($request->get('password-confirmation'), $request->get('new-password')) == 0) {
            return to_route('pw_change',['user'=>$user->id])->withInput()->withErrors(array('password-confirmation' => '新しいパスワードと確認フィールドが一致しません'));
        }




        $user->password=Hash::make($request->get('new-password'));
        $user->save();

        return to_route('ac_info')->with(['message'=>'パスワードが更新されました','status'=>'info']);
    }

    public function pw_change_admin($id)
    {
        $target_user=User::findOrFail($id);

        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info')
        ->first();


        // dd($companies,$areas,$shops);

        return view('user.pw_change_admin',compact('target_user','user'));
    }

    public function pw_update_admin(Request $request,$id)
    {

        $user=User::findOrFail($id);

        $request->validate([

            'new-password' => ['required', Rules\Password::defaults()],
            'password-confirmation' => ['required', Rules\Password::defaults()],
        ]);

        if(!strcmp($request->get('password-confirmation'), $request->get('new-password')) == 0) {
            return to_route('pw_change_admin',['user'=>$user->id])->withInput()->withErrors(array('password-confirmation' => '新しいパスワードと確認フィールドが一致しません'));
        }


        $user->password=Hash::make($request->get('new-password'));
        $user->save();

        return to_route('memberlist')->with(['message'=>'パスワードが更新されました','status'=>'info']);
    }




}
