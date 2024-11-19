<?php

namespace App\Http\Controllers\Mailing;

use App\Http\Requests\Mailing\StoreRequest;
use Illuminate\Support\Facades\DB;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $this->service->store($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
        return redirect()->route('mailing.index');
    }
}
