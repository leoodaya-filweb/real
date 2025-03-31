import { post, showAppLoading, hideAppLoading, spaceType, sweetAlert } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, computed, onUpdated, nextTick, watch } = Vue;

export default {
	emits: ['remove-member', 'show-add-member-form', 'show-private-members', 'hide-private-members', 'save-new-space'],
	props: ['activeSpace', 'spaceGroups', 'privateMembersVisibility', 'currentUser'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { activeSpace, spaceGroups, privateMembersVisibility, currentUser } = toRefs(props);
		const errorSummary = ref('');
		const searchMemberRef = ref('');
		const searchMemberModel = ref('');
		const selectedMembers = ref([]);

		watch(privateMembersVisibility, () => {
		  	if (privateMembersVisibility.value) {
				searchMemberModel.value = '';
				selectedMembers.value = [];
		  	}
		});
		const showMembers = () => {
			emit('show-private-members')
		}
		const closeMembers = () => {
			emit('hide-private-members');
		}
		const showAddMemberForm = () => {
			emit('show-add-member-form')
		}
		
		const filteredSpaceGroups = computed(() => {
			return spaceGroups.value.filter(spaceGroup => {
		        return spaceGroup.fullname.toLowerCase().includes(searchMemberModel.value.toLowerCase())
		    })
		});

		const filteredUsers = computed(() => {
			return activeSpace.value.activeMembers.filter(user => {
		        return user.fullname.toLowerCase().includes(searchMemberModel.value.toLowerCase())
		    })
		});

		const removeMember = (token) => {
			Swal.fire({
		        title: "Are you sure?",
		        text: "You won\"t be able to revert this!",
		        icon: "warning",
		        showCancelButton: true,
		        confirmButtonText: "Confirm",
		        cancelButtonText: "No, cancel!",
		        reverseButtons: true
		    }).then(function(result) {
		        if (result.value) {
					selectedMembers.value = [];
		           	errorSummary.value = '';
					showAppLoading('.chat-modal', 'Removing Member...');

					post('default/remove-member-space', {spaceToken: activeSpace.value.token, token: token})
					.then(response => {
						if (response.status == 'success') {
							toastr.success(response.message);
							emit('remove-member', response);
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
		    });
		}

		const removeSelected = () => {
			removeMember(selectedMembers.value);
		}

		onUpdated(() => {
			nextTick(() => {
				searchMemberRef.value.focus();
			});
		});

		const isSpaceCreator = () => {
			return activeSpace.value.created_by == currentUser.value.id
		}

		const actionAccess = (spaceGroup) => {
			return (activeSpace.value.created_by != spaceGroup.user_id) && activeSpace.value.userIsCreator;
		}

		const createPersonalSpace = (user='', spaceGroup='') => {
			let data = {
				name: '', 
				private_members: [],
				user_id: '',
				type: spaceType.personal
			}

			if (user) {
				data.name = user.fullname;
				data.private_members = [user.id];
				data.user_id = user.id;
			}

			if (spaceGroup) {
				data.name = spaceGroup.fullname;
				data.private_members = [spaceGroup.user_id];
				data.user_id = spaceGroup.user_id;
			}

			showAppLoading('.chat-modal', 'Creating Connection...');
			post('default/create-space', data)
			.then(response => {
				if (response.status == 'success') {
					closeMembers();
					searchMemberModel.value = '';
					selectedMembers.value = [];

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
			filteredSpaceGroups,
			privateMembersVisibility,
			showMembers,
			closeMembers,
			searchMemberRef,
			searchMemberModel,
			showAddMemberForm,
			errorSummary,
			removeMember,
			selectedMembers,
			removeSelected,
			actionAccess,
			isSpaceCreator,
			filteredUsers,
			createPersonalSpace,
			currentUser
		}
	},
  	template: `
		<div>
			<a v-if="! activeSpace.isPersonal" @click.prevent="showMembers" href="#" class="navi-link">
				<span class="navi-icon">
					<span class="svg-icon svg-icon-md svg-icon-primary">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <rect x="0" y="0" width="24" height="24"/>
						        <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
						        <path d="M12,13 C10.8954305,13 10,12.1045695 10,11 C10,9.8954305 10.8954305,9 12,9 C13.1045695,9 14,9.8954305 14,11 C14,12.1045695 13.1045695,13 12,13 Z" fill="#000000" opacity="0.3"/>
						        <path d="M7.00036205,18.4995035 C7.21569918,15.5165724 9.36772908,14 11.9907452,14 C14.6506758,14 16.8360465,15.4332455 16.9988413,18.5 C17.0053266,18.6221713 16.9988413,19 16.5815,19 C14.5228466,19 11.463736,19 7.4041679,19 C7.26484009,19 6.98863236,18.6619875 7.00036205,18.4995035 Z" fill="#000000" opacity="0.3"/>
						    </g>
						</svg>
					</span>
				</span>
				<span class="navi-text"> {{ activeSpace.isPublic ? 'Active': '' }} Members</span>
				<span class="navi-link-badge">
					<span class="label label-light-primary label-rounded font-weight-bold">
						{{ activeSpace.isPrivate ? activeSpace.totalSpaceGroups: activeSpace.activeMembers.length }}
					</span>
				</span>
			</a>
			<Teleport to="#chat-module">
	            <modal v-show="privateMembersVisibility" class="chat-modal" @close="closeMembers">
			        <template v-slot:header>
			            <h3> 
			            	{{ activeSpace.isPrivate ? 'Private': 'Active' }} Members 
			            </h3>
			        </template>

			        <template v-slot:body>
			            <div v-html="errorSummary"></div>

			            <div class="form-group">
			                <input ref="searchMemberRef" type="text" v-model="searchMemberModel" class="form-control form-control-lg" placeholder="Search...">
			            </div>
			            <div v-if="activeSpace.isPrivate">
				            <div v-if="filteredSpaceGroups.length" class="checkbox-list">
			                    <label v-for="spaceGroup in filteredSpaceGroups" :key="spaceGroup.id" class="checkbox checkbox-lg">
									<input v-if="actionAccess(spaceGroup)" type="checkbox" name="selectedMembers" :value="spaceGroup.token" v-model="selectedMembers">
			                        <span v-if="actionAccess(spaceGroup)"></span>
			                        <div class="d-flex">
				                        <img :src="spaceGroup.userPhotoLink" class="img-fluid img-circle-new-space">
				                    	<span class="ml-2">
					                    	<span class="font-weight-bold" v-text="spaceGroup.fullname"></span>
					                    	<span v-if="activeSpace.created_by == spaceGroup.user_id" class="badge badge-primary">Administrator</span>
					                    	<br>
					                    	<span class="text-muted" v-text="spaceGroup.email"></span>
				                    	</span>
				                    </div>

				                    <div class="absolute-right">
				                    	<button v-if="currentUser.id != spaceGroup.user_id" @click="createPersonalSpace('', spaceGroup)" type="button" class="btn btn-outline-primary btn-sm mr-2">
			                                <i class="fab fa-telegram-plane"></i> Send Message
			                            </button>
			                            <a v-if="actionAccess(spaceGroup)" href="#" class="btn btn-icon btn-light-danger btn-sm" @click.prevent="removeMember(spaceGroup.token)">
			                                <i class="fa fa-trash"></i>
			                            </a>
			                        </div>
			                    </label>
			                </div>
			                <div v-else>
			                	No members found
			                </div>
			            </div>

			            <div v-if="activeSpace.isPublic">
				            <div v-if="filteredUsers.length" class="checkbox-list">
			                    <label v-for="user in filteredUsers" :key="user.id" class="checkbox checkbox-lg">
			                        <div class="d-flex">
				                        <img :src="user.photoLink" class="img-fluid img-circle-new-space">
				                    	<span class="ml-2">
					                    	<span class="font-weight-bold" v-text="user.fullname"></span>
					                    	<span v-if="activeSpace.created_by == user.id" class="badge badge-primary">Administrator</span>
					                    	<br>
					                    	<span class="text-muted" v-text="user.email"></span>
				                    	</span>
				                    </div>

				                    <div class="absolute-right">
			                            <button v-if="currentUser.id != user.id" @click="createPersonalSpace(user)" type="button" class="btn btn-outline-primary btn-sm mr-2">
			                                <i class="fab fa-telegram-plane"></i> Send Message
			                            </button>
			                        </div>
			                    </label>
			                </div>
			                <div v-else>
			                	No members found
			                </div>
			            </div>
			        </template>

			        <template v-slot:footer>
		        		<button v-if="selectedMembers.length" class="btn btn-outline-danger font-weight-bold" @click="removeSelected">
			                <i class="fa fa-trash"></i> Remove Selected Members ({{ selectedMembers.length }})
			            </button>
			            <button v-if="activeSpace.canAddPrivateMember" class="btn btn-outline-secondary font-weight-bold" @click="showAddMemberForm">
			                <i class="fa fa-plus"></i> Add Members
			            </button>
			            <button class="btn btn-light-primary font-weight-bold" @click="closeMembers">
			                Close
			            </button>
			        </template>
			    </modal>
		    </Teleport>
		</div>
	`
}