<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\ProductsImport;
use App\Models\ProductsExport;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use App\Models\AuctionProductsImport;
use App\Exports\ExistingSheetReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\AuctionAttribute;
use Illuminate\Validation\ValidationException;



class ProductBulkUploadController extends Controller
{
    public function __construct()
    {

        $this->middleware(['permission:product_bulk_import'])->only('index');
        $this->middleware(['permission:product_bulk_export'])->only('export');
    }

    public function index()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        if (Auth::user()->user_type == 'seller' || Auth::user()?->shop) {
            if (Auth::user()->shop?->verification_status) {
                return view('seller.product_bulk_upload.index',compact('categories'));
            } else {
                flash(translate('Your shop is not verified yet!'))->warning();
                return back();
            }
        } elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.index',compact('categories'));
        }
    }

    public function generate_marketplace_upload_sheet(Request $request)
    {
        try {
            if(empty($request->category_id)){
                throw new \Exception("Category is empty!");
            }

            $category = Category::where('id',$request->category_id)->first();

            $attributes = AuctionAttribute::where("category_id", $request->category_id)->get();

            $fields = [];
            if(!empty($attributes)){
                
                foreach ($attributes as $attribute) {
                    $fields[] = $attribute->fields_name;
                }

            }
            
            $currentDateTime = now();

            $start_date = $currentDateTime->format('d-m-Y H:i:s');

            $futureDateTime = $currentDateTime->copy()->addDays(30);

            $end_date = $futureDateTime->format('d-m-Y H:i:s');

            $existingFilePath = public_path('download/product_bulk_demo.xlsx');

            $sheetData = Excel::toArray(new ExistingSheetReader, $existingFilePath);

            if(isset($sheetData[0][1][2])){
                $sheetData[0][1][2] = $request->category_id;
            }
            
            if(isset($sheetData[0][1][5])){
                $sheetData[0][1][5] = $end_date;
            }

            $sheetData = $sheetData[0] ?? [];

            if (empty($sheetData)) {
                throw new \Exception("Sheet data is empty!");
            }

            if (!empty($fields) && is_array($sheetData[0])) {
                $sheetData[0] = array_merge($sheetData[0], $fields);  
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $requiredFields = ['name','category_id','unit_price']; 
            foreach ($attributes as $attribute) {
                if($attribute->field_optional == 1 ){
                    $requiredFields[] = $attribute->fields_name;
                }
            }

            $styleArray = [
                'font' => [
                    'color' => ['rgb' => 'FF0000'], 
                ],
            ];

            // Write data to the sheet
            foreach ($sheetData as $rowIndex => $row) {
                foreach ($row as $columnIndex => $cellValue) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                    $sheet->setCellValue($columnLetter . ($rowIndex + 1), $cellValue);
                    // Apply red color to required fields
                    if (in_array($cellValue, $requiredFields)) {
                        $sheet->getStyle($columnLetter . ($rowIndex + 1))->applyFromArray($styleArray);
                    }
                }
            }
            $writer = new Xlsx($spreadsheet);
            $newFilePath = public_path('download/marketplace - '.($category->name ?? 'product').'.xlsx');
            $writer->save($newFilePath);

            return response()->download($newFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            flash(__('Something want wrong! '.$e->getMessage()))->error();
            return back();
        }
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();

        return PDF::loadView('backend.downloads.category', [
            'categories' => $categories,
        ], [], [])->download('category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();

        return PDF::loadView('backend.downloads.brand', [
            'brands' => $brands,
        ], [], [])->download('brands.pdf');
    }

    public function pdf_download_seller()
    {
        $users = User::where('user_type', 'seller')->get();

        return PDF::loadView('backend.downloads.user', [
            'users' => $users,
        ], [], [])->download('user.pdf');
    }

    public function bulk_upload(Request $request)
    {

            if ($request->hasFile('bulk_file')) {

                $file = $request->file('bulk_file');

                $sheetData = Excel::toArray(new ExistingSheetReader, $file);
                
                if(isset($sheetData[0][1][2])){
                    $category_id =  $sheetData[0][1][2]; 
                }

                if(empty($category_id)){
                    flash("Category  id is required!")->error();
                    return back();
                }

                $attributes = AuctionAttribute::where("category_id", $category_id)
                ->get(['fields_name', 'field_optional']) 
                ->mapWithKeys(function($attribute) {
                    return [
                        format_to_underscore($attribute->fields_name) => $attribute->field_optional 
                    ];
                })
                ->toArray();

                $import = new ProductsImport($attributes); 
                Excel::import($import, request()->file('bulk_file'));
            }
            return back();

    }

    public function auction_upload()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        if (Auth::user()->user_type == 'seller' || Auth::user()?->shop) {
            if (Auth::user()->shop?->verification_status) {
                return view('auction.frontend.seller.auction_product_bulk_upload.index', compact('categories'));
            } else {
                flash(translate('Your shop is not verified yet!'))->warning();
                return back();
            }
        } elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('auction.auction_products.auction_product_bulk_upload.index', compact('categories'));
        }
    }

    public function generate_auction_upload_sheet(Request $request)
    {
        try {
            if(empty($request->category_id)){
                throw new \Exception("Category is empty!");
            }

            $category = Category::where('id',$request->category_id)->first();

            $attributes = AuctionAttribute::where("category_id", $request->category_id)->get();

            $fields = [];
            if(!empty($attributes)){
                
                foreach ($attributes as $attribute) {
                    $fields[] = $attribute->fields_name;
                }

            }
            
            $currentDateTime = now();

            $start_date = $currentDateTime->copy()->addDays(3)->format('d-m-Y H:i:s');

            $futureDateTime = $currentDateTime->copy()->addDays(7);

            $end_date = $futureDateTime->format('d-m-Y H:i:s');

            $existingFilePath = public_path('download/AuctionProductUpdate.xlsx');

            $sheetData = Excel::toArray(new ExistingSheetReader, $existingFilePath);

            if(isset($sheetData[0][1][6])){
                $sheetData[0][1][6] = $request->category_id;
            }
            // reset lot Numbers
            if(isset($sheetData[0][1][7])){
                $sheetData[0][1][7] = '';
            }
            // reset Auction Numbers
            if(isset($sheetData[0][1][9])){
                $sheetData[0][1][9] = '';
            }

            // reset Brand id
            if(isset($sheetData[0][1][15])){
                $sheetData[0][1][15] = '';
            }


            if(isset($sheetData[0][1][3])){
                $sheetData[0][1][3] =  $start_date;
            }
            

            if(isset($sheetData[0][1][4])){
                $sheetData[0][1][4] =  $end_date;
            }

            $sheetData = $sheetData[0] ?? [];

            if (empty($sheetData)) {
                throw new \Exception("Sheet data is empty!");
            }

            if (!empty($fields) && is_array($sheetData[0])) {
                $sheetData[0] = array_merge($sheetData[0], $fields);  
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();


            
            $requiredFields = ['name','starting_bid','auction_start_date','auction_end_date','category_id','estimate_start','estimate_end']; 
            foreach ($attributes as $attribute) {
                if($attribute->field_optional == 1 ){
                    $requiredFields[] = $attribute->fields_name;
                }
            }

        
            $styleArray = [
                'font' => [
                    'color' => ['rgb' => 'FF0000'], 
                ],
            ];

            foreach ($sheetData as $rowIndex => $row) {
                foreach ($row as $columnIndex => $cellValue) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                    $sheet->setCellValue($columnLetter . ($rowIndex + 1), $cellValue);
                    if (in_array($cellValue, $requiredFields)) {
                        $sheet->getStyle($columnLetter . ($rowIndex + 1))->applyFromArray($styleArray);
                    }
                }
            }
            $writer = new Xlsx($spreadsheet);
            $newFilePath = public_path('download/Auction - '.($category->name ?? 'new_auction_product_upload').'.xlsx');
            $writer->save($newFilePath);

            return response()->download($newFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            flash(__('Something want wrong! '.$e->getMessage()))->error();
            return back();
        }
    }
    public function auction_bulk_upload(Request $request)
    {
            if ($request->hasFile('bulk_file')) {

                $file = $request->file('bulk_file');

                $sheetData = Excel::toArray(new ExistingSheetReader, $file);
                
                if(isset($sheetData[0][1][6])){
                    $category_id =  $sheetData[0][1][6]; 
                }

                if(empty($category_id)){
                    flash("Category id is required !")->error();
                    return back();
                }
                $attributes = AuctionAttribute::where("category_id", $category_id)
                ->get(['fields_name', 'field_optional']) 
                ->mapWithKeys(function($attribute) {
                    return [
                        format_to_underscore($attribute->fields_name) => $attribute->field_optional 
                    ];
                })
                ->toArray();

                $import = new AuctionProductsImport($attributes);

                Excel::import($import, request()->file('bulk_file'));
            }
            return back();
    }
}
