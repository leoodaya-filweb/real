import { post, showAppLoading, hideAppLoading } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, onMounted, nextTick } = Vue;

export default {
	props: ['activeSpace'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { activeSpace } = toRefs(props);

		const formVisibility = ref(false);
		const dropzoneRef = ref('');

		const showForm = () => {
			formVisibility.value = true;
			let objDZ = Dropzone.forElement("#change-space-photo");
			objDZ.emit("resetFiles");

			nextTick(() => {
				dropzoneRef.value.classList.remove('dz-started');
			});
		}
		const hideForm = () => {
			formVisibility.value = false;
		}

		const success = (file, s) => {
			hideAppLoading('.chat-modal');
			if (s.status == 'success') {
				showAppLoading('.chat-modal', 'Replacing Photo...');
				post('default/replace-space-photo', {spaceToken: activeSpace.value.token, fileToken: s.file.token})
				.then(response => {
					if (response.status == 'success') {
						hideForm();
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
		}

		const removedfile = (file) => {
			fileToken.value = '';
		}

		const initDropzone = () => {
			$('#change-space-photo').dropzone({
		        url: chatModule.fileUploadUrl, // Set the url for your upload script location
		        paramName: "UploadForm[fileInput]", // The name that will be used to transfer the file
		        maxFiles: 1,
		        maxFilesize: 10, // MB
		        addRemoveLinks: true,
		        dictRemoveFileConfirmation: 'Remove File ?',
		        dictRemoveFile: 'Remove',
		        acceptedFiles: chatModule.imageAcceptedFiles.join(','),
		        init: function() {
		            this.on("sending", function(file, xhr, formData) {
		            	showAppLoading('.chat-modal', 'File uploading...');
		                formData.append('UploadForm[modelName]', 'Space');
		                formData.append('UploadForm[token]', file.upload.uuid);
		                formData.append(chatModule.csrfParam, chatModule.csrfToken);
		            });
		            this.on('removedfile', removedfile);
		            this.on('complete', function (file) {
		            });
		            this.on('success', success);
		            this.on('resetFiles', function() {
				        if(this.files.length != 0){
				            for(i=0; i<this.files.length; i++){
				                this.files[i].previewElement.remove();
				            }
				            this.files.length = 0;
				        }
				    });
		        }
		    });
		}

		onMounted(() => {
			initDropzone();
		});

		return {
			activeSpace,
			showForm,
			hideForm,
			formVisibility,
			dropzoneRef
		}
	},

  	template: `
		<div>
            <a @click.prevent="showForm" href="#" class="navi-link">
				<span class="navi-icon">
					<span class="svg-icon svg-icon-md svg-icon-primary">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <polygon points="0 0 24 0 24 24 0 24"/>
						        <path d="M6,5 L18,5 C19.6568542,5 21,6.34314575 21,8 L21,17 C21,18.6568542 19.6568542,20 18,20 L6,20 C4.34314575,20 3,18.6568542 3,17 L3,8 C3,6.34314575 4.34314575,5 6,5 Z M5,17 L14,17 L9.5,11 L5,17 Z M16,14 C17.6568542,14 19,12.6568542 19,11 C19,9.34314575 17.6568542,8 16,8 C14.3431458,8 13,9.34314575 13,11 C13,12.6568542 14.3431458,14 16,14 Z" fill="#000000"/>
						    </g>
						</svg>
					</span>
				</span>
				<span class="navi-text">
					Change Photo
				</span>
			</a>

			<Teleport to="#chat-module">
	            <modal v-show="formVisibility" class="chat-modal">
			        <template v-slot:header>
			            <h3> Change Space Photo </h3>
			        </template>

			        <template v-slot:body>
			          	<div class="dropzone dropzone-default dropzone-primary" id="change-space-photo" ref="dropzoneRef">
	                        <div class="dropzone-msg dz-message needsclick">
	                            <h3 class="dropzone-msg-title">
	                                Drop files or click to upload
	                            </h3>
	                            <span class="dropzone-msg-desc">
	                               Upload space your desired photo
	                            </span>
	                        </div>
	                    </div>
			        </template>

			        <template v-slot:footer>
			            <button class="btn btn-light-primary font-weight-bold" @click="hideForm">
			                Close
			            </button>
			        </template>
			    </modal>
		    </Teleport>
		</div>
	`
}