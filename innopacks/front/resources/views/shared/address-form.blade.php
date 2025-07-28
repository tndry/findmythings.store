<form class="needs-validation address-form mb-4" novalidate>
  <input type="hidden" name="id" value="">

  @if(current_customer_id())
    <div class="form-group mb-4">
      <label class="form-label" for="name">
        <span class="text-danger">*</span>
        {{ __('common/address.name') }}
      </label>
      <input type="text" class="form-control" name="name" value="" required
             placeholder="{{ __('common/address.name') }}"/>
      <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
      __('common/address.name')]) }}</span>
    </div>
  @else
    <div class="row gx-2">
      <div class="col-6">
        <div class="form-group mb-4">
          <label class="form-label" for="name">
            <span class="text-danger">*</span>
            {{ __('common/address.name') }}
          </label>
          <input type="text" class="form-control" name="name" value="" required
                 placeholder="{{ __('common/address.name') }}"/>
          <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
          __('common/address.name')]) }}</span>
        </div>
      </div>
      <div class="col-6">
        <div class="form-group mb-4">
          <label class="form-label" for="email">
            <span class="text-danger">*</span>
            {{ __('common/address.email') }}
          </label>
          <input type="text" class="form-control" name="email" value="" required
                 placeholder="{{ __('common/address.email') }}"/>
          <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
          __('common/address.email')]) }}</span>
        </div>
      </div>
    </div>
  @endif

  <div class="form-group mb-4">
    <label class="form-label" for="email">
      <span class="text-danger">*</span>
      {{ __('common/address.address_1') }}</label>
    <input type="text" class="form-control" name="address_1" value="" required
           placeholder="{{ __('common/address.address_1') }}"/>
    <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
      __('common/address.address_1')]) }}</span>
  </div>
  <div class="row gx-2">
    <div class="col-6">
      <div class="form-group mb-4">
        <label class="form-label" for="Address_1">{{ __('common/address.address_2') }}</label>
        <input type="text" class="form-control" name="address_2" value=""
               placeholder="{{ __('common/address.address_2') }}"/>
      </div>
    </div>
    <div class="col-6">
      <div class="form-group mb-4">
        <label class="form-label" for="zipcode">
          <span class="text-danger">*</span>
          {{ __('common/address.zipcode') }}
        </label>
        <input type="text" class="form-control" name="zipcode" value="" required
               placeholder="{{ __('common/address.zipcode') }}"/>
        <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
          __('common/address.zipcode')]) }}</span>
      </div>
    </div>
    <div class="col-6">
      <div class="form-group mb-4">
        <label class="form-label" for="city">
          <span class="text-danger">*</span>
          {{ __('common/address.city') }}
        </label>
        <input type="text" class="form-control" name="city" value="" required placeholder="City"/>
        <span class="invalid-feedback" role="alert">
          {{ __('front/common.error_required', ['name' => __('common/address.city')]) }}
        </span>
      </div>
    </div>
    <div class="col-6">
      <div class="form-group mb-4">
        <label class="form-label" for="country_code">
          <span class="text-danger">*</span>
          {{ __('common/address.country') }}
        </label>
        <select class="form-select" name="country_code" required></select>
        <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
          __('common/address.country')]) }}</span>
      </div>
    </div>
    <div class="col-6">
      <div class="form-group mb-4">
        <label class="form-label" for="state">
          <span class="text-danger">*</span>
          {{ __('common/address.state') }}
        </label>
        <select class="form-select" name="state_code" required disabled></select>
        <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
          __('common/address.state')]) }}</span>
      </div>
    </div>
    <div class="col-6">
      <div class="form-group mb-4">
        <label class="form-label" for="phone">
          <span class="text-danger">*</span>
          {{ __('common/address.phone') }}
        </label>
        <input type="text" class="form-control" name="phone" value="" required
               placeholder="{{ __('common/address.phone') }}"/>
        <span class="invalid-feedback" role="alert">{{ __('front/common.error_required', ['name' =>
          __('common/address.phone')]) }}</span>
      </div>
    </div>

    <div class="col-6">
      <div class="form-group mb-4 d-flex gap-3">
        <label class="form-label" for="default">{{__('front/common.default')}}</label>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="default" name="default" value="1">
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-center">
    <button type="button" class="btn btn-primary btn-lg form-submit w-50">{{ __('front/common.submit') }}</button>
  </div>
</form>

@push('footer')
  <script>
    const settingCountryCode = @json(system_setting('country_code') ?? '');
    const settingStateCode = @json(system_setting('state_code') ?? '');

    inno.validateAndSubmitForm('.address-form', function (data) {
      if (typeof updateAddress === 'function') {
        updateAddress(data);
      }
    });

    getCountries();

    if (settingCountryCode) {
      $('select[name="country_code"]').val(settingCountryCode);
      getZones(settingCountryCode);
    }

    $(document).on('change', 'select[name="country_code"]', function () {
      var countryId = $(this).val();
      getZones(countryId);
    });

    // 获取所有国家数据
    function getCountries() {
      axios.get('{{ front_route('countries.index') }}').then(function (res) {
        var countries = res.data;
        var countrySelect = $('select[name="country_code"]');
        countrySelect.empty();
        countrySelect.append('<option value="">{{ __('front/common.please_choose') }}</option>');
        countries.forEach(function (country) {
          countrySelect.append('<option value="' + country.code + '"' + (country.code == settingCountryCode ? ' selected' : '') + '>' + country.name + '</option>');
        });
      });
    }

    function getZones(countryId, callback = null) {
      axios.get('{{ front_route('countries.index') }}/' + countryId).then(function (res) {
        var zones = res.data;
        var zoneSelect = $('select[name="state_code"]');
        zoneSelect.empty();

        if (zones.length === 0) {
          zoneSelect.prop('disabled', true);
          zoneSelect.prop('required', false);
          zoneSelect.append('<option value="">N/A</option>');
        } else {
          zoneSelect.prop('disabled', false);
          zoneSelect.prop('required', true);
          zoneSelect.append('<option value="">{{ __('front/common.please_choose') }}</option>');
          zones.forEach(function (zone) {
            zoneSelect.append('<option value="' + zone.code + '">' + zone.name + '</option>');
          });
        }

        if (typeof callback === 'function') {
          callback();
        }
      });
    }

    function clearForm() {
      const addressForm = $('.address-form');
      addressForm[0].reset();
      addressForm.removeClass('was-validated');

      addressForm.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    }
  </script>
@endpush
