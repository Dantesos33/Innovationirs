<?php
namespace App\Http\Requests\Parts;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartRequest extends FormRequest
{
    public function authorize(): bool
    {return true;}

    public function rules(): array
    {
        $partId = $this->route('part')?->id;

        return [
            'name'                => 'required|string|max:300',
            'part_number'         => 'nullable|string|max:150',
            'oem_part_number'     => 'nullable|string|max:150',
            'sku'                 => 'nullable|string|max:150|unique:parts,sku,' . $partId,
            'make_id'             => 'nullable|exists:makes,id',
            'equipment_type_id'   => 'nullable|exists:equipment_types,id',
            'condition_type'      => 'required|in:new,used,rebuilt,salvage',
            'short_description'   => 'nullable|string|max:500',
            'description'         => 'nullable|string',
            'compatibility_notes' => 'nullable|string',
            'weight_lbs'          => 'nullable|numeric|min:0',
            'dimensions'          => 'nullable|string|max:100',
            'stock_quantity'      => 'nullable|integer|min:-1',
            'stock_status'        => 'required|in:in_stock,out_of_stock,on_order,call_for_availability',
            'warranty_type'       => 'required|in:none,30_days,90_days,6_months,1_year,2_years,3_years,custom',
            'warranty_notes'      => 'nullable|string|max:300',
            'ships_worldwide'     => 'boolean',
            'is_featured'         => 'boolean',
            'california_prop65'   => 'boolean',
            'sample_image_shown'  => 'boolean',
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:500',
            'status'              => 'required|in:active,inactive,draft,archived',
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category_ids'        => 'nullable|array',
            'category_ids.*'      => 'exists:categories,id',
            'model_ids'           => 'nullable|array',
            'model_ids.*'         => 'exists:equipment_models,id',
        ];
    }
}
