<?php

namespace App\Http\Controllers;

use App\Models\CropImage;
use App\Models\amount;
use Illuminate\Http\Request;
use Storage;
use DB;
class CropImageController extends Controller
{
    public function imageView(){
        $image=CropImage::all();
        return view('crop_image.crop_image',compact('image'));
    }

    public function imageInsert(Request $request){
          $image=new CropImage();
        if(isset($request->image)){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() .'edu'.'.' . $extension;
            $path = 'Backend/image/Student/';
            $file->move($path, $filename);
            $image->image = $path . $filename;
            }
            $image->save();
            return redirect()->back();
    }

    public function saveCroppedImage(Request $request){
        $base64Image = $request->input('crop_image');

        // Check if the base64 image data is present
        if (!$base64Image) {
            return response()->json(['success' => false, 'message' => 'No image data provided'], 400);
        }

        // Extract and decode the image data
        list($type, $data) = explode(';', $base64Image);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        // Generate a unique filename and save the image
        $filename = 'cropped_image_' . time() . '.png';
        Storage::disk('public')->put($filename, $data);

        // Optionally save the filename to the database
        DB::table('crop_images')->insert([
            'crop_image' => $filename,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Image saved successfully']);
    }
            public function bkashView($id){
                $amount=amount::find($id);
                if($amount->amount==0){
                    return redirect()->route('bkash.bkash',[$id])->with('error-alert', 'Amount is zero');
                }
                $amount=$amount->amount;
            return view('bkash.bkash',compact('amount'));
            }

            public function amountView(Request $request){

                $getamount=amount::all();
                return view('Amount.Amount_add', compact('getamount'));
            }

            public function amountInsert(Request $request){
                $amount=new amount();
                $amount->amount=$request->amount;
                $amount->save();
                return redirect()->back();


            }

}
