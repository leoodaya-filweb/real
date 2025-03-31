import { post, sweetAlert, showAppLoading, hideAppLoading } from '../library.js';
const { ref, reactive, watch, onMounted } = Vue;

export default {
	components: {
        Datepicker: VueDatePicker,
	},
	setup(props, { emit }) {
		const form = reactive({
			name: '',
			barangay: '',
			sex: 0,
			type: 0,
			address: '',
			pickup_address: '',
			chief_complaint: '',
			other_complaints: '',
			destination: '',
			date_time: ''
		});

		const date_time = ref(new Date());

		const errorSummary = ref('');
		const sameAsHomeAddress = ref(false);

		const sameAsHomeAddressCallback = () => {
			if (sameAsHomeAddress.value) {
				form.pickup_address = form.address;
		  	}
		}

		const convertTime = () => {
			const _date = new Date(date_time.value);

			const date = _date.toLocaleDateString('en-US');
			const time = _date.toLocaleTimeString('en-US');

			let day = _date.getDate();
			let month = _date.getMonth() + 1;
			let year = _date.getFullYear();

			if (day < 10) {
			    day = `0${day}`;
			}

			if (month < 10) {
			    month = `0${month}`;
			}
			form.date_time = `${year}-${month}-${day} ${time}`;
		}

		watch(date_time, convertTime);

		watch(form, sameAsHomeAddressCallback);
		watch(sameAsHomeAddress, sameAsHomeAddressCallback);

		onMounted(() => {
			convertTime()
		})
		const barangays = [
			'Anoling',
			'Banglos',
			'Batangan',
			'Catablingan',
			'Canaway',
			'Lumutan',
			'Mahabang Lalim',
			'Maigang',
			'Maligaya',
			'Magsikap',
			'Minahan Norte',
			'Minahan Sur',
			'Pagsangahan',
			'Pamplona',
			'Pisa',
			'Poblacion',
			'Sablang',
			'San Marcelino',
			'Umiray'
		];

		const resetForm = () => {
			form.name = '';
			form.address = '';
			form.pickup_address = '';
			form.chief_complaint = '';
			form.other_complaints = '';
			form.destination = '';
			form.sex = 0;
			form.type = 0;
			form.date_time = '';
			sameAsHomeAddress.value = false;
			date_time.value = new Date();
		}

		const submitRequest = () => {
			if (form.name == '' || form.address == '' || form.pickup_address == '' || form.chief_complaint == '') {
				sweetAlert('Please fill up required fields', 'error', 1500);

				return;
			}

			errorSummary.value = '';
			showAppLoading('.request-form', 'Sending Request...');

			post('request/create', form)
			.then(response => {
				if (response.status == 'success') {
					sweetAlert('Request Successfully Sent', 'success', 1500);
					resetForm();
				}
				else {
					Swal.fire('Error', response.errorSummary, 'error');
					errorSummary.value = response.errorSummary;
				}
				hideAppLoading('.request-form');
		    })
		    .catch(e => {
                console.log(e)
				hideAppLoading('.request-form');
            });
		}

		return {
			form,
			errorSummary,
			sameAsHomeAddress,
			submitRequest,
			barangays,
			date_time,
		}
	},
  	template: `
	  	<div class="request-form">
	  		<div v-html="errorSummary"></div>

            <div class="form-group required">
                <label class="control-label">Name of Patient</label>
                <input v-model="form.name" type="text" name="" class="form-control">
            </div>

            <div class="row">
	            <div class="col-md-12">
					<div class="form-group">
						<label>Sex at Birth</label>
						<div class="radio-inline">
							<label class="radio">
								<input value="0" type="radio" name="sex" v-model="form.sex">
								<span></span>Male
							</label>
							<label class="radio">
								<input value="1" type="radio" name="sex" v-model="form.sex">
								<span></span>Female
							</label>
						</div>
					</div>
	            </div>
	            <div class="col-md-12">
					<div class="form-group">
		                <label class="control-label">Barangay</label>
		                <input list="barangays" v-model="form.barangay" type="text" name="" class="form-control">
		                <datalist id="barangays">
		                	<option v-for="(barangay, index) in barangays" :key="index" :value="barangay" />
		                </datalist>
		            </div>
	            </div>
            </div>

            <div class="form-group required">
                <label class="control-label">Home Address of Patient in Gen. Nakar</label>
                <input v-model="form.address" type="text" name="" class="form-control">
            </div>

            

            <div class="form-group required">
                <label class="control-label mb-0">Pick-up Address</label>
                <div class="checkbox-inline">
					<label class="checkbox">
						<input v-model="sameAsHomeAddress" type="checkbox" name="" value="1">
						<span></span>
						check if same as home address
					</label>
				</div>
                <input v-model="form.pickup_address" type="text" name="" class="form-control">
            </div>

            <div class="form-group required">
                <label class="control-label mb-0">Chief Complaint</label><br>
            	<label class="text-dark-50 form-text"> Karamdaman o kalagayan  ng pasyente </label>
                <input v-model="form.chief_complaint" type="text" name="" class="form-control">
            </div>

            <div class="form-group">
                <label class="control-label mb-0">Other Complaints</label><br>
            	<label class="text-dark-50 form-text"> Iba pang kondisyong medikal </label>
                <input v-model="form.other_complaints" type="text" name="" class="form-control">
            </div>

            <div class="form-group">
                <label class="control-label">Destination</label>
                <input v-model="form.destination" type="text" name="" class="form-control">
            </div>


            <div class="form-group">
				<label>Sex at Birth</label>
				<div class="radio-inline">
					<label class="radio">
						<input value="0" type="radio" name="type" v-model="form.type">
						<span></span>Emergency
					</label>
					<label class="radio">
						<input value="1" type="radio" name="type" v-model="form.type">
						<span></span>Patient Transfer
					</label>
				</div>
			</div>

            <div class="form-group" v-show="form.type == 1">
                <label class="control-label">Date & Time</label>
				<datepicker v-model="date_time" :is24="false" utc></datepicker>
				{{dateAndTime}}
            </div>

            <div class="text-right">
                <button @click="submitRequest" type="button" class="btn btn-success font-weight-bold text-uppercase">
                    Send Request
                </button>
            </div>
        </div>
	`
}