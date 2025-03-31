import { get, post, showAppLoading, hideAppLoading } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, onUpdated, nextTick, watch, onMounted } = Vue;

export default {
	emits: ['save-active-space'],
	props: ['activeSpace'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { activeSpace } = toRefs(props);
		const spaceNameModel = ref('');

		const errorSummary = ref('');
		const formVisibility = ref(false);

		const spaceNameRef = ref('');

		const showForm = () => {
			formVisibility.value = true;

			/*get('default/view-space', {token: activeSpace.value.token})
		    .then(response => {
				space.value = response.space;
			})
		    .catch(e => {
                console.log(e)
            });*/
		}
		const hideForm = () => {
			formVisibility.value = false;
		}

		const saveActiveSpace = () => {
			errorSummary.value = '';

			showAppLoading('.chat-modal', 'Updating Space...');

			post('default/update-space', {spaceToken: activeSpace.value.token, name: spaceNameModel.value})
			.then(response => {
				if (response.status == 'success') {
					toastr.success(response.message);
					emit('save-active-space', response);
					hideForm();
				}
				else {
					errorSummary.value = response.errorSummary;
				}
				hideAppLoading('.chat-modal');
		    })
		    .catch(e => {
                console.log(e)
				hideAppLoading('.chat-modal');
            });
		}

		onUpdated(() => {
			nextTick(() => {
				spaceNameRef.value.focus();
			});
		});

		watch(activeSpace, () => {
		  spaceNameModel.value = activeSpace.value.name;
		});

		onMounted(() => {
		  spaceNameModel.value = activeSpace.value.name;
		});


		return {
			formVisibility,
			showForm,
			hideForm,
			errorSummary,
			spaceNameRef,
			saveActiveSpace,
			spaceNameModel,
			activeSpace,
		}
	},
  	template: `
		<span>
            <a v-if="activeSpace.renamable" @click.prevent="showForm" href="#" class="navi-link">
				<span class="navi-icon">
					<span class="svg-icon svg-icon-md svg-icon-primary">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
						        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
						    </g>
						</svg>
					</span>
				</span>
				<span class="navi-text">Rename</span>
			</a>

			<Teleport to="#chat-module">
	            <modal v-show="formVisibility" class="chat-modal">
			        <template v-slot:header>
			            <h3> Rename Space</h3>
			        </template>

			        <template v-slot:body>
			            <div v-html="errorSummary"></div>
			            <div class="form-group">
			                <label>Name</label> 
			                <input ref="spaceNameRef" type="" name="" v-model="spaceNameModel" class="form-control form-control-lg" @keyup.enter="saveActiveSpace">
			            </div>
			        </template>

			        <template v-slot:footer>
			            <button class="btn btn-light-success font-weight-bold" @click="saveActiveSpace">
			                Save
			            </button>

			            <button class="btn btn-light-primary font-weight-bold" @click="hideForm">
			                Close
			            </button>
			        </template>
			    </modal>
		    </Teleport>
		</span>
	`
}
