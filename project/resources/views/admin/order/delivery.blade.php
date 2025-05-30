@extends('layouts.load')

@section('content')
<div class="content-area">
    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area" id="modalEdit">
                        @include('alerts.admin.form-error')
                        <form id="geniusformdata" action="{{route('admin-order-update',$data->id)}}" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}

                            <h4 class="heading">{{ __('Current Delivery Information') }}</h4>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <p>{{ __('Delivery Provider') }}:</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <p>{{ $data->delivery_provider ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <p>{{ __('Shipping Service') }}:</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <p>{{ $data->shipping_service_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <p>{{ __('Tracking Number') }}:</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <p>{{ $data->tracking_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <p>{{ __('Shipping Label URL') }}:</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    @if($data->shipping_label_url)
                                        <p><a href="{{ $data->shipping_label_url }}" target="_blank">{{ $data->shipping_label_url }}</a></p>
                                    @else
                                        <p>N/A</p>
                                    @endif
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <p>{{ __('Shipping Rate Cost') }}:</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <p>{{ $data->currency_sign }}{{ number_format($data->shipping_rate_cost * $data->currency_value, 2) }}</p>
                                </div>
                            </div>

                            <hr>
                            <h4 class="heading">{{ __('Update Delivery Information (Manual Override / API)') }}</h4>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Delivery Provider') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="delivery_provider">
                                        <option value="" {{ !$data->delivery_provider ? 'selected' : '' }}>{{ __('None/Manual') }}</option>
                                        <option value="DHL" {{ $data->delivery_provider == 'DHL' ? 'selected' : '' }}>{{ __('DHL') }}</option>
                                        <option value="FedEx" {{ $data->delivery_provider == 'FedEx' ? 'selected' : '' }}>{{ __('FedEx') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Shipping Service Name') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="shipping_service_name" placeholder="{{ __('Shipping Service Name') }}" value="{{ $data->shipping_service_name }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Tracking Number') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="tracking_number" placeholder="{{ __('Tracking Number') }}" value="{{ $data->tracking_number }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Shipping Label URL') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="shipping_label_url" placeholder="{{ __('Shipping Label URL') }}" value="{{ $data->shipping_label_url }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Payment Status') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="payment_status" required="">
                                        <option value="Pending" {{$data->payment_status == 'Pending' ? "selected":""}}>{{ __('Unpaid') }}</option>
                                        <option value="Completed" {{$data->payment_status == 'Completed' ? "selected":""}}>{{ __('Paid') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Delivery Status') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="status" required="">
                                        <option value="pending" {{ $data->status == "pending" ? "selected":"" }}>{{ __('Pending') }}</option>
                                        <option value="processing" {{ $data->status == "processing" ? "selected":"" }}>{{ __('Processing') }}</option>
                                        <option value="on delivery" {{ $data->status == "on delivery" ? "selected":"" }}>{{ __('On Delivery') }}</option>
                                        <option value="completed" {{ $data->status == "completed" ? "selected":"" }}>{{ __('Completed') }}</option>
                                        <option value="declined" {{ $data->status == "declined" ? "selected":"" }}>{{ __('Declined') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Track Note') }} *</h4>
                                        <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <textarea class="input-field" name="track_text" placeholder="{{ __('Enter Track Note Here') }}"></textarea>
                                </div>
                            </div>

                            <hr>
                            <h4 class="heading">{{ __('Generate Shipping Label (API)') }}</h4>

                            @if($dhlSettings && $dhlSettings->is_active)
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn" type="submit" name="action" value="generate_dhl_label">{{ __('Generate DHL Label') }}</button>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p class="text-warning">{{ __('DHL API is not active or configured.')}}</p>
                                    </div>
                                </div>
                            @endif

                            @if($fedexSettings && $fedexSettings->is_active)
                                <div class="row mt-2">
                                     <div class="col-lg-4">
                                        <div class="left-area">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn" type="submit" name="action" value="generate_fedex_label">{{ __('Generate FedEx Label') }}</button>
                                    </div>
                                </div>
                            @else
                                 <div class="row">
                                    <div class="col-lg-12">
                                        <p class="text-warning">{{ __('FedEx API is not active or configured.')}}</p>
                                    </div>
                                </div>
                            @endif


                            <br>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Any additional scripts can go here --}}
@endsection
