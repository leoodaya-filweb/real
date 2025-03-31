import { post, sweetAlert, spaceType, showAppLoading, hideAppLoading, truncateString, isObjectEmpty } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, nextTick, computed } = Vue;

export default {
	emit: ['save-new-space'],
	props: ['users'],
	components: {
		Modal,
	},
	setup(props, { emit }) {

		const { users } = toRefs(props);
		const searchUserModel = ref('');
		const searchUserRef = ref('');
		const formVisibility = ref(false);
		const selected = ref([]);

		const showForm = () => {
			formVisibility.value = true;
			nextTick(() => {
				searchUserRef.value.focus();
			});
		}

		const hideForm = () => {
			formVisibility.value = false;
		}

		const createPersonalSpace = (user) => {
			let data = {
				name: user.fullname, 
				private_members: [user.id],
				user_id: user.id,
				type: spaceType.personal
			}

			showAppLoading('.chat-modal', 'Creating Connection...');
			post('default/create-space', data)
			.then(response => {
				if (response.status == 'success') {
					// sweetAlert('Space Created');
					hideForm();
					searchUserModel.value = '';
					selected.value = [];
					
					emit('save-new-space', response);
				}
				else {
					sweetAlert(response.errorSummary, 'danger');
				}

				hideAppLoading('.chat-modal');
		    })
		    .catch(e => {
                console.log(e)
				hideAppLoading('.chat-modal');
            });
		}

		const saveNewSpace = () => {
			showAppLoading('.chat-modal', 'Creating Space...');

			let names = [];
			if (selected.value.length) {
				for (var i = 0; i < selected.value.length; i++) {
					
					let foundUser = users.value.find((user) => {
						return user.id == selected.value[i];
					});

					if (! isObjectEmpty(foundUser)) {
						names.push(foundUser.fullname);
					}

				}
			}
			const dataSpaceName = names.length ? truncateString(names.join(', '), 30): 'Private Space: ' + Math.floor(Date.now() / 1000);

			let data = {
				name: dataSpaceName, 
				private_members: selected.value,
				type: spaceType.private
			}

			post('default/create-space', data)
			.then(response => {
				if (response.status == 'success') {
					sweetAlert('Space Created');
					hideForm();
					searchUserModel.value = '';
					selected.value = [];
					
					emit('save-new-space', response);
				}
				else {
					sweetAlert(response.errorSummary, 'danger');
				}

				hideAppLoading('.chat-modal');
		    })
		    .catch(e => {
                console.log(e)
				hideAppLoading('.chat-modal');
            });
		}
		const filteredUsers = computed(() => {
			return users.value.filter(user => {
		        return user.fullname.toLowerCase().includes(searchUserModel.value.toLowerCase())
		    })
		});

		return {
			searchUserModel,
			filteredUsers,
			formVisibility,
			showForm,
			hideForm,
			searchUserRef,
			selected,
			saveNewSpace,
			createPersonalSpace
		}
	},
  	template: `
	  	<div class="w-49">
	  		<button type="button" @click="showForm" class="btn btn-block btn-outline-secondary font-weight-bold text-uppercase text-center">
	            NEW CHAT
	        </button>
			<modal v-show="formVisibility" class="chat-modal">
		        <template v-slot:header>
		            <h3> Find Chat </h3>
		        </template>

		        <template v-slot:body>
		            <div class="form-group">
	                	<label class="font-weight-bold">Selected Users ({{ selected.length }})</label>
	                	<div class="mb-2">
	                		<input autocomplete="off" ref="searchUserRef" v-model="searchUserModel" class="form-control form-control-lg" placeholder="Search user">
	                	</div>
		                <div v-if="filteredUsers.length" class="checkbox-list">
		                    <label v-for="user in filteredUsers" :key="user.id" class="checkbox checkbox-lg">
		                        <input v-model="selected" :value="user.id" name="private_members" class="checkbox" type="checkbox">
		                        <span></span>
		                        <div class="d-flex">
			                        <img :src="user.photoLink" class="img-fluid img-circle-new-space">
			                    	<span class="ml-2">
				                    	<span class="font-weight-bold" v-text="user.fullname"></span> <br>
				                    	<span class="text-muted" v-text="user.email"></span>
			                    	</span>
			                    </div>

			                    <div class="absolute-right">
		                            <button type="button" class="btn btn-outline-primary btn-sm" @click="createPersonalSpace(user)">
		                                <i class="fab fa-telegram-plane"></i> Send Message
		                            </button>
		                        </div>
		                    </label>
		                </div>
		                <div v-else>
		                	No users found
		                </div>
		            </div>
		        </template>

		        <template v-slot:footer>
		            <button @click="saveNewSpace" v-if="selected.length" type="button" class="btn btn-success font-weight-bold">
		                Create Private Space
		            </button>
		            <button class="btn btn-light-primary font-weight-bold" @click="hideForm">
		                Close
		            </button>
		        </template>
		    </modal>
	    </div>
	`
}