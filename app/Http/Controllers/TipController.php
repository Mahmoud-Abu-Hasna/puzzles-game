<?php

namespace App\Http\Controllers;

use App\Enigma;
use App\Tip;
use App\User_tip;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class TipController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function showEnigmaTips($enigma_id){
        if(is_numeric($enigma_id)){
            $enigma = Enigma::find($enigma_id);
            if(! is_null($enigma)){
                $tips = $enigma->tips;

                return view('admin.pages.tips',compact(['tips','enigma_id']));
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

    public function postNewTip(){
        $this->validate(request(), [
            'type' => 'required',
            'tip_value_image' => 'required_if:type,==,image|image',
            'tip_value' => 'required_if:type,==,text',
            'tip_value_ar' => 'required_if:type,==,text',
            'charge' => 'required',
            'discount' => 'numeric|digits_between:1,200',
        ]);

        $tip = new Tip();
        $tip->charge = request('charge');
        $tip->discount = request('discount');
        $tip->type = request('type');
        $tip->admin_id = auth()->id();
        $tip->enigma_id = request('enigma_id');
        if(request('type') == 'image'){
            if (request()->hasFile('tip_value_image')) {
                File::exists(public_path('storage/tip_images/')) or File::makeDirectory(public_path('storage/tip_images/'), 0777, true);
                $image_name = 'tip_' . time();
                $tip_image = Image::make(request()->file('tip_value_image'));
                $tip_image->save(public_path('storage/tip_images/' . $image_name . '.png'));
                $tip_image_uri = 'storage/tip_images/' . $image_name . '.png';
            }
            $tip->tip_value = $tip_image_uri;
            $tip->tip_value_ar = '';
        }else{
            $tip->tip_value = request('tip_value');
            $tip->tip_value_ar = request('tip_value_ar');
        }
        $tip->save();
        session()->flash('flash_message', 'Tip was saved successfully.');
        session()->flash('message_type', 'success');
        return redirect('/admin/enigma/'.request('enigma_id').'/tips');

    }
    public function postEditTip(){
        $this->validate(request(), [
            'tip_id' => 'required|numeric',
            'type' => 'required',
            'tip_value_image' => 'image',
            'tip_value' => 'required_if:type,==,text',
            'tip_value_ar' => 'required_if:type,==,text',
            'charge' => 'required',
            'discount' => 'numeric|digits_between:1,200',
        ]);
        $tip = Tip::find(request('tip_id'));
        $old_value = $tip->tip_value;
        $enigma_id = $tip->enigma_id;
        $tip->charge = request('charge');
        $tip->discount = request('discount');
        $tip->type = request('type');
        $tip->admin_id = auth()->id();
        if(request('type') == 'image'){
            if (request()->hasFile('tip_value_image')) {
                File::exists(public_path('storage/tip_images/')) or File::makeDirectory(public_path('storage/tip_images/'), 0777, true);
                $image_name = 'tip_' . time();
                $tip_image = Image::make(request()->file('tip_value_image'));
                $tip_image->save(public_path('storage/tip_images/' . $image_name . '.png'));
                $tip_image_uri = 'storage/tip_images/' . $image_name . '.png';
                File::delete(public_path($tip->tip_value));
                $tip->tip_value = $tip_image_uri;
                $tip->tip_value_ar = '';
            }else{
                $tip->tip_value = $old_value;
                $tip->tip_value_ar = '';
            }

        }else{
            File::delete(public_path($tip->tip_value));
            $tip->tip_value = request('tip_value');
            $tip->tip_value_ar = request('tip_value_ar');
        }
        $tip->save();
        session()->flash('flash_message', 'Tip was updated successfully.');
        session()->flash('message_type', 'success');
        return redirect('/admin/enigma/'.$enigma_id.'/tips');
    }
    public function postDeleteTip(){
        $this->validate(request(), [
            'tip_id' => 'required|numeric',
        ]);
        $tip = Tip::find(request('tip_id'));
        $enigma_id = $tip->enigma_id;
        if(! is_null($tip)){
            $user_tips = User_tip::where('tip_id','==',request('tip_id'))->get();
            if(count($user_tips)>0){
                $tip->is_published = 0;
                $tip->save();
                session()->flash('flash_message', 'Tip was used so it will be converted to not published status.');
                session()->flash('message_type', 'danger');

            }else{
                File::delete(public_path($tip->tip_value));
                $tip->delete();
                session()->flash('flash_message', 'Tip was deleted successfully.');
                session()->flash('message_type', 'success');
            }
        }else{
            session()->flash('flash_message', 'the selected Tip does not exist.');
            session()->flash('message_type', 'danger');
        }
        return redirect('/admin/enigma/'.$enigma_id.'/tips');
    }
    public function publishTip($tip_id){
        if(is_numeric($tip_id)){
            $tip = Tip::find($tip_id);
            $enigma_id = $tip->enigma_id;
            if(! is_null($tip)){
                $is_published = $tip->is_published == 0?1:0;
                $tip->is_published = $is_published;
                $tip->save();
                session()->flash('flash_message', 'Tip was updated successfully.');
                session()->flash('message_type', 'success');
                return redirect('/admin/enigma/'.$enigma_id.'/tips');
            }else {
                session()->flash('flash_message', 'the selected tip does not exist.');
                session()->flash('message_type', 'danger');
                return redirect()->route('enigmas');

            }

        }else{
            session()->flash('flash_message', 'the selected tip does not exist.');
            session()->flash('message_type', 'danger');
            return redirect()->route('enigmas');
        }
    }
}
