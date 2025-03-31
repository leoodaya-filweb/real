import { post, showAppLoading, hideAppLoading } from '../library.js';

import PrivateMembersButton from './PrivateMembersButton.js';
import AddPrivateMemberButton from './AddPrivateMemberButton.js';
import EditSpaceButton from './EditSpaceButton.js';
import SpaceInfoButton from './SpaceInfoButton.js';
import ViewFilesButton from './ViewFilesButton.js';
import ChangePhotoButton from './ChangePhotoButton.js';

const { toRefs, ref } = Vue;

export default {
	emits: ['remove-member', 'save-member', 'save-active-space', 'leave-space', 'save-new-space'],
	props: ['activeSpace', 'spaceGroups', 'availableUsers', 'currentUser'],
	components: {
		PrivateMembersButton,
		AddPrivateMemberButton,
		EditSpaceButton,
		SpaceInfoButton,
		ViewFilesButton,
		ChangePhotoButton
	},
	setup(props, { emit }) {
		const { activeSpace, spaceGroups, availableUsers, currentUser } = toRefs(props);

		const formVisibility = ref(false);
		const privateMembersVisibility = ref(false);

		const removeMember = (response) => {
			emit('remove-member', response);
		}
		const saveMember = (response) => {
			emit('save-member', response);
		}
		const showAddMemberForm = () => {
			formVisibility.value = true;
			hidePrivateMembers();
		}
		const hideAddMemberForm = () => {
			formVisibility.value = false;
		}

		const showPrivateMembers = () => {
			privateMembersVisibility.value = true;
			hideAddMemberForm();
		}

		const hidePrivateMembers = () => {
			privateMembersVisibility.value = false;
		}

		const saveActiveSpace = (response) => {
			emit('save-active-space', response);
		}

		const isSpaceCreator = () => {
			return activeSpace.value.created_by == currentUser.value.id
		}

		const leaveSpace = () => {
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
					showAppLoading('#chat-module', 'Leaving Space...');
					post('default/leave-space', {user_id: currentUser.value.id, space_id: activeSpace.value.id})
				    .then(response => {
				       	if (response.status == 'success') {
				       		emit('leave-space', response);
				       	}
						hideAppLoading('#chat-module');
				    })
				    .catch(e => {
						hideAppLoading('#chat-module');

				    	console.log(e);
		            });
		        } 
		    });
		}

		const blockConversation = () => {
			Swal.fire({
		        title: "Are you sure?",
		        text: "You won\"t be able to send messages to this space!",
		        icon: "warning",
		        showCancelButton: true,
		        confirmButtonText: "Confirm",
		        cancelButtonText: "No, cancel!",
		        reverseButtons: true
		    }).then(function(result) {
		        if (result.value) {
					showAppLoading('#chat-module', 'Blocking Conversation..');
					post('default/block-space', {user_id: currentUser.value.id, space_id: activeSpace.value.id})
				    .then(response => {
				       	if (response.status == 'success') {

				       	}
						hideAppLoading('#chat-module');
				    })
				    .catch(e => {
						hideAppLoading('#chat-module');

				    	console.log(e);
		            });
		        } 
		    });
		}

		const unblockConversation = () => {
			Swal.fire({
		        title: "Are you sure?",
		        text: "Other members can send messages again.",
		        icon: "warning",
		        showCancelButton: true,
		        confirmButtonText: "Confirm",
		        cancelButtonText: "No, cancel!",
		        reverseButtons: true
		    }).then(function(result) {
		        if (result.value) {
					showAppLoading('#chat-module', 'Unblocking Conversation..');
					post('default/unblock-space', {user_id: currentUser.value.id, space_id: activeSpace.value.id})
				    .then(response => {
				       	if (response.status == 'success') {

				       	}
						hideAppLoading('#chat-module');
				    })
				    .catch(e => {
						hideAppLoading('#chat-module');

				    	console.log(e);
		            });
		        } 
		    });
		}

		const saveNewSpace = (response) => {
			emit('save-new-space', response);
		}

		return {
			activeSpace,
			spaceGroups,
			availableUsers,
			removeMember,
			saveMember,
			formVisibility,
			showAddMemberForm,
			hideAddMemberForm,
			privateMembersVisibility,
			showPrivateMembers,
			hidePrivateMembers,
			saveActiveSpace,
			currentUser,
			isSpaceCreator,
			leaveSpace,
			saveNewSpace,
			blockConversation,
			unblockConversation
		}
	},
  	template: `
  		<div class="d-content">
			<div class="text-left flex-grow-1">
				<div data-bs-auto-close="inside" class="dropdown dropdown-inline" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
					<a href="#" class="btn btn-outline-secondary font-weight-bold btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-list"></i> 
					</a>
					<div class="dropdown-menu dropdown-menu-md" style="">
						<ul class="navi navi-hover py-5">
							<li class="navi-item" v-if="! activeSpace.isPersonal">
								<private-members-button :current-user="currentUser" :private-members-visibility="privateMembersVisibility" :active-space="activeSpace" :space-groups="spaceGroups" @show-add-member-form="showAddMemberForm" @remove-member="removeMember"
									@save-new-space="saveNewSpace" @show-private-members="showPrivateMembers" @hide-private-members="hidePrivateMembers"></private-members-button>
							</li>
							<li class="navi-item" v-if="activeSpace.canAddPrivateMember">
								<add-private-member-button :current-user="currentUser" :form-visibility="formVisibility" :active-space="activeSpace" :available-users="availableUsers" @show-add-member-form="showAddMemberForm" @hide-add-member-form="hideAddMemberForm" @save-new-space="saveNewSpace" @save-member="saveMember" @show-private-members="showPrivateMembers"></add-private-member-button>
							</li>
							<li class="navi-item" v-if="activeSpace.userIsCreator" >
								<edit-space-button :active-space="activeSpace" @save-active-space="saveActiveSpace"></edit-space-button>
							</li>

							<li class="navi-item" v-if="activeSpace.photoChangable">
								<change-photo-button :active-space="activeSpace"></change-photo-button>
							</li>

							<li class="navi-item">
								<view-files-button :active-space="activeSpace"></view-files-button>
							</li>
							<li class="navi-separator my-3"></li>
							
							<li class="navi-item" v-if="activeSpace.blockableConversation">
								<a href="#" class="navi-link" @click.prevent="blockConversation">
									<span class="navi-icon">
										<span class="svg-icon svg-icon-md svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												        <rect x="0" y="0" width="24" height="24"/>
												        <path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" fill="#000000"/>
												        <path d="M6,14 C6.55228475,14 7,14.4477153 7,15 L7,17 C7,17.5522847 6.55228475,18 6,18 C5.44771525,18 5,17.5522847 5,17 L5,15 C5,14.4477153 5.44771525,14 6,14 Z M6,21 C5.44771525,21 5,20.5522847 5,20 C5,19.4477153 5.44771525,19 6,19 C6.55228475,19 7,19.4477153 7,20 C7,20.5522847 6.55228475,21 6,21 Z" fill="#000000" opacity="0.3"/>
												    </g>
												</svg>
										</span>
									</span>
									<span class="navi-text">
										Block Conversation
									</span>
								</a>
							</li>

							<li class="navi-item" v-if="activeSpace.unblockableConversation">
								<a href="#" class="navi-link" @click.prevent="unblockConversation">
									<span class="navi-icon">
										<span class="svg-icon svg-icon-md svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
											        <path d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z" fill="#000000"/>
											    </g>
											</svg>
										</span>
									</span>
									<span class="navi-text">
										Unblock Conversation
									</span>
								</a>
							</li>

							<li class="navi-item" v-if="activeSpace.leavable">
								<a href="#" class="navi-link" @click.prevent="leaveSpace">
									<span class="navi-icon">
										<span class="svg-icon svg-icon-md svg-icon-primary">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <polygon points="0 0 24 0 24 24 0 24"/>
											        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1"/>
											        <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
											    </g>
											</svg>
										</span>
									</span>
									<span class="navi-text">
										Leave
									</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
	        </div>
	        <div class="text-center flex-grow-1">
	        	<div class="d-flex justify-content-center align-items-center">
	        		<div v-if="activeSpace.displayImageUrl">
		    			<img :src="activeSpace.displayImageUrl" class="img-fluid img-circle h30px">
					</div>
		            <div class="text-dark-75 ml-2"> 
		                <span class="text-dark-75 font-weight-bold font-size-h5" v-text="activeSpace.displayName"></span>
			            <div class="text-left">
			                <span class="label label-dot label-success"></span> &nbsp;
			                <span class="font-weight-bold text-muted font-size-sm" v-text="activeSpace.typeLabel"> </span>
			            </div>
		            </div>
	        	</div>
	        </div>
	        <div class="text-right flex-grow-1">
	            <space-info-button :active-space="activeSpace"></space-info-button>
	        </div>
        </div>
	`
}