<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;

class ServiceAddController extends Controller
{
    public function execute(Request $request){


        if($request->isMethod('post')){
            $input = $request->all();
            $validator = Validator::make($input, [
                'name'=>'required|max:255',
                'text'=>'required',
                'icon'=>'required'
            ]);
            
            if($validator->fails()){
                return redirect('admin')->withErrors($validator)->withInput();
            }
            
            $service = new Service();
            $service->fill($input);
            if($service->save()){
                return redirect('admin')->with('status','Сервис добавлен');
            }
        }

        if(view()->exists('admin.content_services_add')){
            $data = [
                'title'=>'Новый сервис',
            ];
            return view('admin.services_add',$data);
        }
    }
}
