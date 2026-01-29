<div class="container mt-4">
    <div class="card card-primary">
        <div class="card-header text-center">
            <h5>تعديل المنتج - {{ $data->name }} -</h5>
        </div>

        <form wire:submit.prevent="update" enctype="multipart/form-data" role="form">
            <div class="card-body">

                {{-- الاسم --}}
                <div class="form-group mb-3">
                    <label for="name">ادخل الاسم الجديد</label>
                    <input wire:model="name" type="text" class="form-control" id="name"
                           placeholder="{{ $data->name }}">
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- السعر والخصم --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price">السعر</label>
                        <input wire:model="price" type="text" class="form-control" id="price"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d*\.\d*).*$/, '$1').replace(/^(\d*\.).*?\./, '$1')"
                               placeholder="{{ $data->price }}">
                        @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="offer_rate">الخصم (%)</label>
                        <input wire:model="offer_rate" type="text" class="form-control" id="offer_rate"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')"
                               placeholder="{{ $data->offer_rate }}">
                        @error('offer_rate') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- القسم وحالة المنتج --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="section">القسم الجديد</label>
                        <select wire:model="section" id="section" class="form-control">
                            <option value="">اختر القسم الجديد</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section') <div class="text-danger"> الرجاء اختيار القسم </div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="qnt">حالة المنتج</label>
                        <select wire:model="qnt" id="qnt" class="form-control">
                            <option value="">اختر الحالة الجديدة</option>
                            <option value="1">متوفر</option>
                            <option value="0">غير متوفر</option>
                        </select>
                        @error('qnt') <div class="text-danger"> الرجاء اختيار حالة المنتج </div> @enderror
                    </div>
                </div>

                {{-- الأكثر مبيعاً و يظهر في الاول --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input wire:model="bestSaller" type="checkbox" class="form-check-input" id="bestSaller" value="1" {{ $data->best_saller ? 'checked' : '' }}>
                            <label class="form-check-label" for="bestSaller">الأكثر مبيعاً</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input wire:model="comeFirst" type="checkbox" class="form-check-input" id="comeFirst" value="1" {{ $data->come_first ? 'checked' : '' }}>
                            <label class="form-check-label" for="comeFirst">يظهر في الأول</label>
                        </div>
                    </div>
                </div>

                {{-- الوصف --}}
                <div class="form-group mb-3">
                    <label for="description">الوصف</label>
                    <input wire:model="description" type="text" class="form-control" id="description"
                           placeholder="{{ $data->description }}">
                </div>

                {{-- رفع الصورة --}}
                <div class="form-group mb-3">
                    <label for="photo">اختر الصورة الجديدة</label>
                    <input wire:model="photo" type="file" class="form-control" id="photo">
                    @if ($photo)
                        <img class="border mt-2 w-25" src="{{ $photo->temporaryUrl() }}">
                    @else
                        <img class="border mt-2 w-25" src="{{ asset('uploads/' . $data->photo) }}">
                    @endif
                    @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- رسالة النجاح --}}
                @if (session('done'))
                    <div class="alert alert-success d-flex align-items-center mt-3">
                        <i class="fa fa-check-circle me-2"></i>
                        <span>تم تعديل المنتج بنجاح</span>
                    </div>
                @endif

            </div>

            {{-- الفوتر --}}
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    تحديث
                </button>
            </div>
        </form>
    </div>
</div>
