<?php

namespace App\Http\Controllers;

use App\Enigma;
use App\Tip;
use App\User_enigma;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class EnigmaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(){
        $enigmas = Enigma::all();
        return view('admin.pages.enigmas',compact('enigmas'));
    }

    public function postAddEnigma(){
        $this->validate(request(), [
            'type' => 'required',
            'enigma_value_image' => 'required_if:type,==,image|image',
            'enigma_value' => 'required_if:type,==,text',
            'enigma_value_ar' => 'required_if:type,==,text',
            'correct_answer' => 'required',
            'prize' => 'numeric|digits_between:1,200',
        ]);

        $enigma = new Enigma();
        $enigma->prize = request('prize');
        $enigma->correct_answer = request('correct_answer');
        $enigma->type = request('type');
        $enigma->admin_id = auth()->id();
        if(request('type') == 'image'){
            if (request()->hasFile('enigma_value_image')) {
                File::exists(public_path('storage/enigma_images/')) or File::makeDirectory(public_path('storage/enigma_images/'), 0777, true);
                $image_name = 'enigma_' . time();
                $enigma_image = Image::make(request()->file('enigma_value_image'));
                $enigma_image->save(public_path('storage/enigma_images/' . $image_name . '.png'));
                $enigma_image_uri = 'storage/enigma_images/' . $image_name . '.png';
            }
            $enigma->enigma_value = $enigma_image_uri;
            $enigma->enigma_value_ar ='';
        }else{
            $enigma->enigma_value = request('enigma_value');
            $enigma->enigma_value_ar = request('enigma_value_ar');
        }
        $enigma->save();
        session()->flash('flash_message', 'Enigma was saved successfully.');
        session()->flash('message_type', 'success');
        return redirect()->route('enigmas');

    }
    public function postEditEnigma(){
        $this->validate(request(), [
            'enigma_id' => 'required|numeric',
            'type' => 'required',
            'enigma_value_image' => 'image',
            'enigma_value' => 'required_if:type,==,text',
            'enigma_value_ar' => 'required_if:type,==,text',
            'correct_answer' => 'required',
            'prize' => 'numeric|digits_between:1,200',
        ]);
        $enigma = Enigma::find(request('enigma_id'));
        $old_value = $enigma->enigma_value;
        $enigma->prize = request('prize');
        $enigma->correct_answer = request('correct_answer');
        $enigma->type = request('type');
        $enigma->admin_id = auth()->id();
        if(request('type') == 'image'){
            if (request()->hasFile('enigma_value_image')) {
                File::exists(public_path('storage/enigma_images/')) or File::makeDirectory(public_path('storage/enigma_images/'), 0777, true);
                $image_name = 'enigma_' . time();
                $enigma_image = Image::make(request()->file('enigma_value_image'));
                $enigma_image->save(public_path('storage/enigma_images/' . $image_name . '.png'));
                $enigma_image_uri = 'storage/enigma_images/' . $image_name . '.png';
                File::delete(public_path($enigma->enigma_value));
                $enigma->enigma_value = $enigma_image_uri;
                $enigma->enigma_value_ar ='';
            }else{
                $enigma->enigma_value = $old_value;
                $enigma->enigma_value_ar ='';
            }

        }else{
            File::delete(public_path($enigma->enigma_value));
            $enigma->enigma_value = request('enigma_value');
            $enigma->enigma_value_ar = request('enigma_value_ar');
        }
        $enigma->save();
        session()->flash('flash_message', 'Enigma was updated successfully.');
        session()->flash('message_type', 'success');
        return redirect()->route('enigmas');
    }
    public function postDeleteEnigma(){
        $this->validate(request(), [
            'enigma_id' => 'required|numeric',
        ]);
        $enigma = Enigma::find(request('enigma_id'));
        if(! is_null($enigma)){
            $user_enigmas = User_enigma::where('enigma_id','==',request('enigma_id'))->get();
            if(count($user_enigmas)>0){
                $enigma->is_published = 0;
                $enigma->save();
                session()->flash('flash_message', 'Enigma was used so it will be converted to not published status.');
                session()->flash('message_type', 'danger');

            }else{
                File::delete(public_path($enigma->enigma_value));
                $enigma->delete();
                $tips_photos = Tip::where('enigma_id','=',request('enigma_id'))->pluck('tip_value');
                Tip::where('enigma_id','=',request('enigma_id'))->delete();
                if(count($tips_photos)){
                    foreach ($tips_photos as $tips_photo){
                        File::delete(public_path($tips_photo));
                    }
                }
                session()->flash('flash_message', 'Enigma and its tips was deleted successfully.');
                session()->flash('message_type', 'success');
            }
        }else{
            session()->flash('flash_message', 'the selected enigma does not exist.');
            session()->flash('message_type', 'danger');
        }
        return redirect()->route('enigmas');
    }
    public function publishEnigma($enigma_id){
        if(is_numeric($enigma_id)){
            $enigma = Enigma::find($enigma_id);
            if(! is_null($enigma)){
                $is_published = $enigma->is_published == 0?1:0;
                $enigma->is_published = $is_published;
                $enigma->save();
                session()->flash('flash_message', 'Enigma was updated successfully.');
                session()->flash('message_type', 'success');
                return redirect()->route('enigmas');
            }else {
                session()->flash('flash_message', 'the selected enigma does not exist.');
                session()->flash('message_type', 'danger');
                return redirect()->route('enigmas');

            }

        }else{
            session()->flash('flash_message', 'the selected enigma does not exist.');
            session()->flash('message_type', 'danger');
            return redirect()->route('enigmas');
        }
    }
}
