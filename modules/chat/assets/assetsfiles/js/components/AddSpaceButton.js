import { post, sweetAlert, spaceType, showAppLoading, hideAppLoading } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, onUpdated, nextTick, computed } = Vue;

export default {
	emits: ['save-new-space'],
	props: ['users'],
	components: {
		Modal,
	},
	setup(props, { emit }) {

		const { users } = toRefs(props);

		const errorSummary = ref('');
		const formVisibility = ref(false);
		const spaceRef = ref('');
		const searchUserModel = ref('');

		const space = ref({
			name: '',
			type: spaceType.public,
			private_members: []
		});

		const resetFormSpaceState = () => {
			space.value = {
				name: '',
				type: spaceType.public,
				private_members: []
			};
		}
		const saveNewSpace = () => {
			errorSummary.value = '';
			showAppLoading('.chat-modal', 'Creating Space...');

			post('default/create-space', space.value)
			.then(response => {
				if (response.status == 'success') {
					sweetAlert('Space Created');
					formVisibility.value = false;
					searchUserModel.value = '';
					resetFormSpaceState();
					emit('save-new-space', response);
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

		const showForm = () => {
			formVisibility.value = true;
		}

		const hideForm = () => {
			formVisibility.value = false;
			errorSummary.value = '';
		}

		const filteredUsers = computed(() => {
			return users.value.filter(user => {
		        return user.fullname.toLowerCase().includes(searchUserModel.value.toLowerCase())
		    })
		});

		onUpdated(() => {
			nextTick(() => {
				spaceRef.value.focus();
			});
		});

		return {
			showForm,
			hideForm,
			space,
			spaceType,
			errorSummary,
			users,
			saveNewSpace,
			spaceRef,
			formVisibility,
			filteredUsers,
			searchUserModel
		}
	},
  	template: `
	  	<div class="w-49">
	  		<a @click.prevent="showForm" href="#" class="btn btn-block btn-primary font-weight-bold text-uppercase text-center">
	            New Space
	        </a>
			<modal v-show="formVisibility" class="chat-modal">
		        <template v-slot:header>
		            <h3> Create New Space </h3>
		        </template>

		        <template v-slot:body>
		            <div v-html="errorSummary"></div>
		            <div class="form-group">
		                <label>Name</label>
		                <input ref="spaceRef" type="text" name="name" v-model="space.name" class="form-control form-control-lg" @keyup.enter="saveNewSpace" placeholder="Space Name">
		            </div>

		            <div class="form-group type-options">
		                <label>Type</label>
		                <div class="row">
		                    <div class="col-lg-6">
		                        <label class="option">
		                            <span class="option-control">
		                                <span class="radio">
		                                    <input type="radio" name="type" v-model="space.type" :value="spaceType.public">
		                                    <span></span>
		                                </span>
		                            </span>
		                            <span class="option-label">
		                                <span class="option-head">
		                                    <span class="option-title">Public</span>
		                                </span>
		                                <span class="option-body text-dark-75">
		                                    Available to all users
		                                </span>
		                            </span>
		                        </label>
		                    </div>
		                    <div class="col-lg-6">
		                        <label class="option">
		                            <span class="option-control">
		                                <span class="radio">
		                                    <input type="radio" name="type" v-model="space.type" :value="spaceType.private">
		                                    <span></span>
		                                </span>
		                            </span>
		                            <span class="option-label">
		                                <span class="option-head">
		                                    <span class="option-title">Private</span>
		                                </span>
		                                <span class="option-body text-dark-75">
		                                    Limited to selected users.
		                                </span>
		                            </span>
		                        </label>
		                    </div>
		                </div>
		            </div>

		            <div class="form-group" v-if="space.type == spaceType.private">
	                	<label class="font-weight-bold">Selected Users ({{ space.private_members.length }})</label>
	                	<div class="mb-2">
	                		<input v-model="searchUserModel" class="form-control" placeholder="Search user">
	                	</div>
		                <div v-if="filteredUsers.length" class="checkbox-list">
		                    <label v-for="user in filteredUsers" :key="user.id" class="checkbox checkbox-lg">
		                        <input :value="user.id" name="private_members" class="checkbox" type="checkbox" v-model="space.private_members">
		                        <span></span>
		                        <div class="d-flex">
			                        <img :src="user.photoLink" class="img-fluid img-circle-new-space">
			                    	<span class="ml-2">
				                    	<span class="font-weight-bold" v-text="user.fullname"></span> <br>
				                    	<span class="text-muted" v-text="user.email"></span>
			                    	</span>
			                    </div>
		                    </label>
		                </div>
		                <div v-else>
		                	No users found
		                </div>
		            </div>
		        </template>

		        <template v-slot:footer>
		            <button type="submit" class="btn btn-success font-weight-bold" @click="saveNewSpace">
		                Save
		            </button>
		            <button class="btn btn-light-primary font-weight-bold" @click="hideForm">
		                Close
		            </button>
		        </template>
		    </modal>
	    </div>
	`
}