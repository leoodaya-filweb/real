import Modal from './Modal.js';

const { toRefs, ref} = Vue;

export default {
	props: ['activeSpace'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { activeSpace } = toRefs(props);
		const infoVisibility = ref(false);

		const showInfo = () => {
			infoVisibility.value = true;
		}
		const hideInfo = () => {
			infoVisibility.value = false;
		}
		return {
			activeSpace,
			infoVisibility,
			showInfo,
			hideInfo
		}
	},
  	template: `
		<span>
           <button class="btn btn-outline-secondary btn-icon" @click="showInfo">
	        	<i class="fas fa-info"></i>
	        </button>

			<Teleport to="#chat-module">
	            <modal v-show="infoVisibility" class="chat-modal">
			        <template v-slot:header>
			            <h3> Space Information</h3>
			        </template>

			        <template v-slot:body>
			            <table class="table table-bordered active-space-table-data">
							<tbody>
                                <tr v-if="activeSpace.displayImageUrl">
						    		<th>Photo</th>
						    		<td>
						    			<img :src="activeSpace.displayImageUrl" class="img-fluid img-circle">
						    		</td>
								</tr>

								<tr>
						    		<th>Name</th>
						    		<td v-text="activeSpace.displayName"></td>
								</tr>
								<tr>
						    		<th>Type</th>
						    		<td v-text="activeSpace.typeLabel"></td>
								</tr>
								<tr v-if="! activeSpace.isPersonal">
						    		<th>Total {{ activeSpace.isPublic ? 'Active': '' }} Members</th>
						    		<td>
						    			{{ activeSpace.isPublic ? activeSpace.totalSpaceGroups.length: activeSpace.totalSpaceGroups }}
						    		</td>
								</tr>
								<tr>
						    		<th>Total Messages</th>
						    		<td v-text="activeSpace.totalSpaceMessages"></td>
								</tr>
								<tr>
						    		<th>Total Files</th>
						    		<td v-text="activeSpace.files.length"></td>
								</tr>
								<tr>
						    		<th>Conversation Status</th>
						    		<td>
						    			{{ activeSpace.is_block ? 'Block': 'Allow' }}
						    		</td>
								</tr>
								<tr>
						    		<th>Created</th>
						    		<td v-text="activeSpace.createdAt"></td>
								</tr>
								<tr>
						    		<th>Created By</th>
						    		<td v-text="activeSpace.createdByEmail"></td>
								</tr>
							</tbody>
						</table>
			        </template>

			        <template v-slot:footer>
			            <button class="btn btn-light-primary font-weight-bold" @click="hideInfo">
			                Close
			            </button>
			        </template>
			    </modal>
		    </Teleport>
		</span>
	`
}


