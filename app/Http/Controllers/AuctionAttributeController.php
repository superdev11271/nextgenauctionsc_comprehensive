<?php

namespace App\Http\Controllers;

use App\Models\AttributeProduct;
use App\Models\AuctionAttribute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Null_;

// This controller is to handle Attribute and categories relation and creation
class AuctionAttributeController extends Controller
{
    public function auction_attribues()
    {
        return view("auction.auction_products.attribues.index");
    }

    public function addDropDownValue(Request $request)
    {
        $ddvalue = $request->current_values;
        $ddvalues = substr($ddvalue, 0, -1);
        $dropvalue = explode(',', $ddvalues);


        $res = "";
        $i = 1;
        foreach ($dropvalue as $index => $dropdownvalue) {
            $i = $index + 1;
            $res .= "<div id='optionid$index'>" .
                "<button class='btn btn-soft-danger btn-icon btn-circle btn-sm m-2' onclick='deleteoption($index,`$dropdownvalue`)' type='button' title='Delete'>" .
                "<i class='las la-trash'></i> </button> <strong>$i ) $dropdownvalue</strong> </div>";
        }
        return $res;
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'category_id' => 'required',
            'fields_name' => 'required',
            'field_type' => 'required',
            'added_by' => 'nullable',
            'field_optional' => 'required',
            'hidenvalues' => [Rule::RequiredIf(function () {
                return request()->field_type >= 3;
            })]
        ], [
            'fields_name.required' => 'Attribute title is required.',
            'field_type.required' => 'Attribute type is required.',
            'hidenvalues.required' => 'Attribute Values are required.',
        ]);
        // dd($validData, $request->toArray());

        $validData["dd_value"] = $validData["hidenvalues"];
        $attribute = new AuctionAttribute;
        $attribute->fill($validData);
        $attribute->save();
        return redirect()->route("auction.attibutes")->with("lastCategory", $validData["category_id"]);
    }

    public function update(Request $request, AuctionAttribute $attribute)
    {
        $validData = $request->validate([
            'fields_name' => 'required',
            'field_type' => 'required',
            'added_by' => 'nullable',
            'field_optional' => 'required',
            'hidenvalues' => [Rule::RequiredIf(function () {
                return request()->field_type >= 3;
            })]
        ], [
            'fields_name.required' => 'Attribute title is required.',
            'field_type.required' => 'Attribute type is required.',
            'hidenvalues.required' => 'Attribute Values are required.',
        ]);


        $validData["dd_value"] = $validData['field_type'] >= 3 ? $validData["hidenvalues"] : null;

        $attribute->fill($validData)->save();
        $lastCategory = $attribute->category_id;
        return redirect()->route("auction.attibutes")->with("lastCategory", $lastCategory);
    }
    public function show_category_attributes(Request $request)
    {
        $attributes = AuctionAttribute::where("category_id", $request->category_id)->get();
        $html = "";
        foreach ($attributes as $attr) {
         
            $isRequired =  $attr->field_optional == 1 ? "<span class='text-danger'>*</span>" : "";
            $type = $attr->field_type_str();
            $deleteUrl = route("auction.attribute.delete", encrypt($attr->id));
            $editUrl = route("auction.attibute.edit", encrypt($attr->id));
            $html .= "<tr>
            <td>$attr->id</td>
            <td>$attr->fields_name $isRequired</td>
            <td>$type</td>
            <td style='max-width:200px'>$attr->dd_value</td>
            <td>
            <a class='btn btn-soft-primary btn-icon btn-circle btn-sm'
            href='$editUrl' title='Edit'>
            <i class='las la-edit'></i></a>

            <a class='btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete'
            href='#' data-href='$deleteUrl' title='Delete'>
                <i class='las la-trash'></i>
            </a>
            </td>
            </tr>";
        }
        return $html;
    }


    public function destroy($attribute)
    {
        $attribute = AuctionAttribute::find(decrypt($attribute));
        $attribute->delete();
        flash(translate('Attribute has been deleted successfully.'))->success();
        return redirect()->route("auction.attibutes")->with("lastCategory", $attribute["category_id"]);;
    }

    public function edit($attribute)
    {
        $attribute = AuctionAttribute::find(decrypt($attribute));
        $attribute_list = $attribute->siblings()->get();
        return view("auction.auction_products.attribues.index", compact("attribute", "attribute_list"));
    }


    public function get_attributes_by_subcategory(Request $request)
    {
        $product_id = $request->product_id??null; //EditPage
        $attributes = AuctionAttribute::where("category_id", $request->category_id)->get();
        $res = view("auction.auction_products.attribues.components.attribute", compact("attributes","product_id"))->render();
        return ["view"=>$res,"status"=>$attributes->count()?true:false];
    }

}
