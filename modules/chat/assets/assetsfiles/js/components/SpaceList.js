import { appState } from '../library.js';
const { toRefs } = Vue;

export default {
    emits: ['select-space'],
	props: ['spaces', 'activeSpace'],
	setup(props, { emit }) {

		const { spaces, activeSpace } = toRefs(props);

		const selectSpace = (space) => {
			emit('select-space', space);
		}
		
		return {
			spaces,
			appState,
			activeSpace,
			selectSpace 
		}
	},
  	template: `
	  	<div>
	  		<div v-if="spaces.length">
                <div v-for="space in spaces" class="navi-item my-2" :key="space.id">
                    <a href="#" class="navi-link space-item" :class="{active: space.token == activeSpace.token}" @click.prevent="selectSpace(space)">
                        <span class="navi-icon mr-4">
                            <div class="symbol symbol-35 symbol-circle symbol-light-primary mr-3">
                                <span v-if="space.displayImageUrl" class="symbol-label"> 
                                    <img :src="space.displayImageUrl" class="img-fluid img-circle">
                                </span>
                                <span v-else class="symbol-label" v-text="space.firstLetter"> </span>
                            </div>
                        </span> 
                        <span class="navi-text">
                            {{ space.displayName }}
                            <div class="text-muted"> 
                                {{space.typeLabel}}
                                <small class="text-danger font-weight-bold" v-if="space.is_block">&nbsp; (Blocked) </small>
                            </div>
                            
                        </span>

                        <div v-if="space.totalSpaceNotificationsByUser > 0">
                            <span class="navi-label">
                                <span class="label label-danger font-weight-bolder" v-text="space.totalSpaceNotificationsByUser"> </span>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
            <div v-else>
                <div class="text-center text-muted font-weight-bold">
                    {{ appState.isLoading ? 'Loading': 'No Space found.' }}
                </div>
            </div>
	  	</div>
	`
}