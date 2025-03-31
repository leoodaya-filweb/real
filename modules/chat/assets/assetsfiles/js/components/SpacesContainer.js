import AddSpaceButton from './AddSpaceButton.js';
import SpaceList from './SpaceList.js';
import ChatButton from './ChatButton.js';

const { toRefs, ref, computed } = Vue;

export default {
	emits: ['save-new-space', 'select-space'],
	props: ['spaces', 'activeSpace', 'users'],
	components: {
		AddSpaceButton,
		SpaceList,
		ChatButton
	},
	setup(props, { emit }) {
		const { spaces, activeSpace, users } = toRefs(props);

		const searchSpaceModel = ref('');

		const saveNewSpace = (response) => {
			emit('save-new-space', response);
		}

		const selectSpace = (space) => {
			emit('select-space', space);
		}

		const filteredSpaces = computed(() => {
			return spaces.value.filter(space => {
		        return space.displayName.toLowerCase().includes(searchSpaceModel.value.toLowerCase())
		    });
		});

		return {
			spaces,
			searchSpaceModel,
			saveNewSpace,
			filteredSpaces,
			activeSpace,
			users,
			selectSpace
		}
	},
  	template: `
	  	<div>
			<div class="mb-5 px-3">
				<div class="d-flex justify-content-between">
					<add-space-button :users="users" @save-new-space="saveNewSpace"></add-space-button>
					<chat-button :users="users" @save-new-space="saveNewSpace"></chat-button>
				</div>
	        </div>

	        <div v-if="spaces.length" class="search-input-container px-3">
	            <div class="input-group input-group-lg input-group-solid my-2">
	                <input type="text" class="form-control pl-4 search-input" placeholder="Search..." v-model="searchSpaceModel">

	                <div class="input-group-append">
	                    <span class="input-group-text pr-3">
	                        <span class="svg-icon svg-icon-lg">
	                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
	                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
	                                    <rect x="0" y="0" width="24" height="24"></rect>
	                                    <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
	                                    <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
	                                </g>
	                            </svg>
	                        </span>
	                    </span>
	                </div>
	            </div>
	            <div class="separator separator-solid my-2"></div>
	        </div>
	    
	        <div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon overflow-auto space-list">
	            <space-list :spaces="filteredSpaces" :active-space="activeSpace" @select-space="selectSpace"></space-list>
	        </div>
        </div>
	`
}