<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetsCategories;
use App\Models\AssetsDetailAssets;
use App\Models\DetailAssets;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class AssertImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try {
            foreach ($collection as $key => $value){

                $assetsCategory = AssetsCategories::firstOrCreate(
                    [
                        'company_id' => 1,
                        'category_name' => $value[0]
                    ],
                    [
                        'category_name' => $value[0]
                    ]
                );
//                dd($value);

                //tim nhan vien duoc uy quyen dung tai san cty
                if ($value[6] != null) {
                    Log::info($value[6]);
                    $employee_id = Employee::where('employee_id', $value[6])->first()->user_id;
                } else {
                    $employee_id = null;
                }

                //trạng thái sản phẩm
                if($value[4] == 'Hoạt động') {
                    $is_working = 1;
                } else if ($value[4] == 'Hư hỏng') {
                    $is_working = 2;
                } else if ($value[4] == 'Tồn kho') {
                    $is_working = 0;
                }

                //data
                $asset = [
                    'assets_category_id' => $assetsCategory->assets_category_id, //danh mục tài sản
                    'name' => $value[1], // tên tài sản
                    'asset_note' => null, // ghi chú tài sản
                    'company_asset_code' => $value[3], // mã tài sản
                    'is_working' => $is_working, // trạng thái sản phẩm(hoạt động = 1, hỏng = 2, tồn kho = 0)
                    'employee_id' => $employee_id, // ủy quyền cho nhân viên nào
                    'company_id' => 1, //công ty
                    'price' => 0, // giá của tài sản
                ];

                $success = Asset::create($asset);

                $tags = explode(',',$value[1]);
                if ($success) {
                    foreach ($tags as $tag) {
                        $slug = Str::slug(trim($tag), '_');
                        if ($slug == '') {
                            continue;
                        } else {
                            $data_detail_asset = [
                                'slug' => $slug,
                                'type' => 1,
                                'status' => 1
                            ];
                            $detailAssets = DetailAssets::firstOrCreate(
                                $data_detail_asset,
                                [
                                    'title' => trim($tag)
                                ]
                            );

                            $dataAssetDetailAsset = [
                                'assets_id' => $success->assets_id,
                                'detail_assets_id' => $detailAssets->id
                            ];

                            AssetsDetailAssets::create($dataAssetDetailAsset);
                        }

                    }
                }


            }
        } catch (\Exception $e) {
            echo 'Lỗi nhé bro ...';
            throw $e;
        }

    }
}
