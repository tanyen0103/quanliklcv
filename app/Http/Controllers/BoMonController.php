<?php

namespace App\Http\Controllers;

use App\Exports\BoMonExport;
use App\Models\BoMon;
use App\Models\Khoa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BoMonController extends Controller
{
    //
    public function getDanhSach()
	{
		$bomon = BoMon::paginate(getSoDong());
        $khoa =Khoa::all();
		$makhoa='';
		return view('dashboard.supmanager.danhmuc.bomon', compact('bomon','khoa','makhoa'));
	}
	
	public function postDanhSach_Khoa(Request $request)
	{
		$bomon = BoMon::where('MaKhoa',$request->MaKhoa)->paginate(getSoDong());
        $khoa =Khoa::all();
		$makhoa=$request->MaKhoa;
		return view('dashboard.supmanager.danhmuc.bomon', compact('bomon','khoa','makhoa'));
	}
    public function postThem(Request $request)
	{
		$this->validate($request, [
            'MaBoMon' => ['required', 'string', 'max:5', 'unique:bomon'],
			'TenBoMon' => ['required', 'string', 'max:191','unique:bomon,TenBoMon'],
            'MaKhoa' => ['required', 'string', 'max:5']
		]);
		
		$orm = new BoMon();
		$orm->MaBoMon = $request->MaBoMon;
		$orm->TenBoMon = $request->TenBoMon;
        $orm->MaKhoa = $request->MaKhoa;
		$orm->save();
		
		return redirect()->route('supmanager.bomon');
	}
    public function postSua(Request $request)
	{

		$this->validate($request, [
            'MaBoMon_edit' => ['required', 'string', 'max:5', 'unique:bomon,MaBoMon,'. $request->id_edit.',MaBoMon'],
			'TenBoMon_edit' => ['required', 'string', 'max:191','unique:bomon,TenBoMon,'. $request->id_edit.',MaBoMon'],
            'MaKhoa_edit' => ['required', 'string', 'max:5'],
		]);
		
		$orm = BoMon::find($request->id_edit);
		$orm->MaBoMon = $request->MaBoMon_edit;
		$orm->TenBoMon = $request->TenBoMon_edit;
        $orm->MaKhoa = $request->MaKhoa_edit;
		$orm->save();
		
		return redirect()->route('supmanager.bomon');
	}
    public function postXoa(Request $request)
	{
		$orm = BoMon::find($request->MaBoMon_delete);
		$orm->delete();
		
		return redirect()->route('supmanager.bomon');
	}
	public function postNhap_SupManager(Request $request)
	{
		try
		{
			$import = new BoMon();
			$import->import($request->file('file_excel'));
			
			if(count($import->failures()) > 0)
			{
				$messages = 'C??c d??ng b??? l???i:';
				foreach($import->failures() as $failure)
				{
					$messages .= '<br />D??ng: <b>' . $failure->row() . '</b>';
					$messages .= '; Thu???c t??nh: <b>' . $failure->attribute() . '</b>';
					$messages .= '; L???i: <b>' . implode('</b>, <b>', $failure->errors()) . '</b>';
				}
				return redirect()->route('supmanager.bomon')->with('warning', $messages);
			}
			else
			{
				return redirect()->route('supmanager.bomon')->with('success', '???? nh???p d??? li???u th??nh c??ng!');
			}
		}
		catch(\Maatwebsite\Excel\Validators\ValidationException $e)
		{
			$failures = $e->failures();
			$messages = 'C??c d??ng b??? l???i:';
			foreach($failures as $failure)
			{
				$messages .= '<br />D??ng: <b>' . $failure->row() . '</b>';
				$messages .= '; Thu???c t??nh: <b>' . $failure->attribute() . '</b>';
				$messages .= '; L???i: <b>' . implode('</b>, <b>', $failure->errors()) . '</b>';
			}
			return redirect()->route('supmanager.bomon')->with('warning', $messages);
		}
	}
	
	public function getXuat_SupManager()
	{
		return Excel::download(new BoMonExport(), 'bomon.xlsx');
	}
}
