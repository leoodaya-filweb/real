import { post, showAppLoading, hideAppLoading, spaceType } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, computed, onUpdated, nextTick, watch } = Vue;

export default {
	emits: ['save-member', 'show-add-member-form', 'hide-add-member-form', 'show-private-members', 'save-new-space'],
	props: ['activeSpace', 'availableUsers', 'formVisibility', 'currentUser'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { activeSpace, availableUsers, formVisibility, currentUser } = toRefs(props);
		const errorSummary = ref('');
		const searchUserRef = ref('');
		const searchUserModel = ref('');
		const selectedUsers = ref([]);


		watch(formVisibility, () => {
		  	if (formVisibility.value) {
				searchUserModel.value = '';
				selectedUsers.value = [];
		  	}
		});

		const showForm = () => {
			emit('show-add-member-form');
		}

		const hideForm = () => {
			emit('hide-add-member-form');
		}

		const saveMember = (userId) => {
			errorSummary.value = '';
			showAppLoading('.chat-modal', 'Adding Member...');

			post('default/add-member-space', {spaceToken: activeSpace.value.token, userId: userId})
			.then(response => {
				if (response.status == 'success') {
					selectedUsers.value = [];
					toastr.success(response.message);
					emit('save-member', response);
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

		const showPrivateMembers = () => {
			emit('show-private-members');
		}

		const addSelected = () => {
			saveMember(selectedUsers.value);
		}

		const filteredAvailableUsers = computed(() => {
			return availableUsers.value.filter(user => {
		        return user.fullname.toLowerCase().includes(searchUserModel.value.toLowerCase())
		    })
		});

		const isSpaceCreator = () => {
			return activeSpace.value.created_by == currentUser.value.id
		}

		onUpdated(() => {
			nextTick(() => {
				searchUserRef.value.focus();
			});
		});


		const createPersonalSpace = (user='') => {
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
					hideForm();
					searchUserModel.value = '';
					selectedUsers.value = [];

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

		return {
			activeSpace,
			formVisibility,
			errorSummary,
			searchUserRef,
			searchUserModel,
			filteredAvailableUsers,
			hideForm,
			showForm,
			saveMember,
			showPrivateMembers,
			selectedUsers,
			addSelected,
			isSpaceCreator,
			createPersonalSpace
		}
	},

  	template: `
		<div>
            <a v-if="activeSpace.isPrivate" @click.prevent="showForm" href="#" class="navi-link">
				<span class="navi-icon">
					<span class="svg-icon svg-icon-md svg-icon-primary">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24"></polygon>
								<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
								<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
							</g>
						</svg>
					</span>
				</span>
				<span class="navi-text">Add Members</span>
			</a>

			<Teleport to="#chat-module">
	            <modal v-show="formVisibility" class="chat-modal">
			        <template v-slot:header>
			            <h3> Add Private Members </h3>
			        </template>

			        <template v-slot:body>
			            <div v-html="errorSummary"></div>
			            <div class="form-group">
			                <input ref="searchUserRef" type="text" v-model="searchUserModel" class="form-control form-control-lg" placeholder="Search...">
			            </div>

			            <div v-if="filteredAvailableUsers.length" class="checkbox-list">
		                    <label v-for="user in filteredAvailableUsers" :key="user.id" class="checkbox checkbox-lg">
								<input v-if="activeSpace.userIsCreator" type="checkbox" name="selectedUsers" :value="user.id" v-model="selectedUsers">
		                        <span v-if="activeSpace.userIsCreator"></span>
		                        <div class="d-flex">
			                        <img :src="user.photoLink" class="img-fluid img-circle-new-space">
			                    	<span class="ml-2">
				                    	<span class="font-weight-bold" v-text="user.fullname"></span> <br>
				                    	<span class="text-muted" v-text="user.email"></span>
			                    	</span>
			                    </div>

			                    <div class="absolute-right">
			                    	<button @click="createPersonalSpace(user)" type="button" class="btn btn-outline-primary btn-sm mr-2">
		                                <i class="fab fa-telegram-plane"></i> Send Message
		                            </button>
		                            <a v-if="activeSpace.userIsCreator" href="#" class="btn btn-icon btn-light-success btn-sm" @click.prevent="saveMember(user.id)">
		                                <i class="fa fa-plus"></i>
		                            </a>
		                        </div>
		                    </label>
		                </div>
		                <div v-else>
		                	No users found
		                </div>
			        </template>

			        <template v-slot:footer>
			        	<button v-if="selectedUsers.length && activeSpace.userIsCreator" class="btn btn-outline-success font-weight-bold" @click="addSelected">
			                <i class="fa fa-plus"></i> Add Selected Members ({{ selectedUsers.length }})
			            </button>

			        	<button class="btn btn-outline-secondary font-weight-bold" @click="showPrivateMembers">
			                <i class="fas fa-users"></i> View Members
			            </button>
			            <button class="btn btn-light-primary font-weight-bold" @click="hideForm">
			                Close
			            </button>
			        </template>
			    </modal>
		    </Teleport>
		</div>
	`
}