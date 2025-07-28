<!-- Logistics Information Settings -->
<div class="tab-pane fade" id="tab-setting-logistics-information">
<div class="container">
  <div class="row">
    <div class="ml-4" id="logistics-form">
      <div class="row col-7">
        <table class="table table-response align-middle table-bordered">
          <thead>
          <tr>
            <th>{{ __('panel/setting.express_company') }}</th>
            <th>{{ __('panel/setting.express_code') }}</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(item,index) in text" :key="index">
            <td data-title="State" class="col-5">
              <el-input v-model="item.company" placeholder="{{ __('panel/setting.express_company') }}"/>
            </td>
            <td data-title="Remark" class="col-5">
              <el-input v-model="item.code" placeholder="{{ __('panel/setting.express_code_hint') }}"/>
            </td>
            <td data-title="Update Time" class="col-1">
              <i class="bi bi-x-circle text-danger cursor-pointer" @click="removeInput(index)"></i>
            </td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td><i @click="addInput" class="bi bi-plus-circle cursor-pointer"></i></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<input type="hidden" name="logistics" :value="JSON.stringify(text)" />

<script>
  const {createApp, ref, watch} = Vue;
  const {CirclePlusFilled} = ElementPlus;

  const logisticsApp = createApp({
    setup() {
      const logisticsData = @json(system_setting('logistics')) || [];
      const text = ref(Array.isArray(logisticsData) ? logisticsData.map(logistic => ({
        company: logistic.company || '',
        code: logistic.code || ''
      })) : []);

      const addInput = () => {
        text.value.push({
          company: '',
          code: ''
        });
      };

      const removeInput = (index) => {
        text.value.splice(index, 1);
      };

      watch(text, (newValue) => {
        const logisticsInput = document.querySelector('input[name="logistics"]');
        if (logisticsInput) {
          logisticsInput.value = JSON.stringify(newValue || []);
        }
      }, { deep: true });

      return {
        text,
        addInput,
        removeInput
      };
    }
  });
  logisticsApp.use(ElementPlus);
  logisticsApp.mount('#logistics-form');
</script>
</div>
