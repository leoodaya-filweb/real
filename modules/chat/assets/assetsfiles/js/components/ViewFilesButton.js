import Modal from './Modal.js';

const { toRefs, ref, onUpdated, nextTick, computed } = Vue;

export default {
	props: ['activeSpace'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { activeSpace} = toRefs(props);

		const fileVisibility = ref(false);
		const searchFileRef = ref('');
		const searchFileModel = ref('');

		const showFiles = () => {
			fileVisibility.value = true;
		}

		const hideFiles = () => {
			fileVisibility.value = false;
		}

		const filteredFiles = computed(() => {
			return activeSpace.value.files.filter(file => {
		        return file.name.toLowerCase().includes(searchFileModel.value.toLowerCase())
		    })
		});

		const viewFile = (file) => {
			window.open(file.viewerUrl);
		}

		onUpdated(() => {
			nextTick(() => {
				searchFileRef.value.focus();
			});
		});

		return {
			activeSpace,
			fileVisibility,
			showFiles,
			hideFiles,
			searchFileRef,
			searchFileModel,
			filteredFiles,
			viewFile
		}
	},

  	template: `
		<div>
            <a href="#" class="navi-link" @click.prevent="showFiles">
				<span class="navi-icon">
					<span class="svg-icon svg-icon-md svg-icon-primary">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <polygon points="0 0 24 0 24 24 0 24"/>
						        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
						        <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
						        <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
						    </g>
						</svg>
					</span>
				</span>
				<span class="navi-text">Files</span>
				<span class="navi-link-badge">
					<span class="label label-light-success label-rounded font-weight-bold" v-text="activeSpace.files.length"></span>
				</span>
			</a>

			<Teleport to="#chat-module">
	            <modal v-show="fileVisibility" class="chat-modal">
			        <template v-slot:header>
			            <h3> Attach Files </h3>
			        </template>

			        <template v-slot:body>
			            <div class="form-group">
			                <input ref="searchFileRef" type="text" v-model="searchFileModel" class="form-control form-control-lg" placeholder="Search...">
			            </div>
			            <label class="font-weight-bold">Total Files Found: {{filteredFiles.length}}</label>

			            <div v-if="filteredFiles.length">
		                    <div v-for="file in filteredFiles" :key="file.id" class="view-attachment-files-container">
		                    	<div class="message-attachments d-flex text-dark-50 align-items-center p-2 mt-1 justify-content-between">
					                <div class="d-flex align-items-center">
					                    <div @click="viewFile(file)" :title="file.name">
					                        <img :src="file.displayPath" class="img-fluid br4px">
					                    </div>
					                    <div class="mx-3" :title="file.name">
					                        <div class="font-weight-bold">
					                            <a class="text-dark-65 text-hover-primary" :href="file.viewerUrl" target="_blank">
					                            	{{ file.name }}.{{ file.extension }}
					                            </a>
					                        </div>
					                        <div v-html="file.fileSize"></div>
					                        <div>
					                        	{{ file.timeSent }}
					                        </div>
					                    </div>
					                </div>
					                <a title="Download" download :href="file.downloadUrl" class="btn btn-icon btn-outline-secondary">
					                    <i class="fa fa-download"></i>
					                </a>
		                    	</div>
		                    	<div class="text-dark-50 py-1 pl-2 bg-light-secondary">
		                    		Uploaded By: {{ file.createdByName }} ({{ file.ago }})
		                    	</div>
				            </div>
		                </div>
		                <div v-else>
		                	No files found
		                </div>
			        </template>

			        <template v-slot:footer>
			            <button class="btn btn-light-primary font-weight-bold" @click="hideFiles">
			                Close
			            </button>
			        </template>
			    </modal>
		    </Teleport>
		</div>
	`
}