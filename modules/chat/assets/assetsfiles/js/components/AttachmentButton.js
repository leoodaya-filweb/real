import { post, showAppLoading, hideAppLoading } from '../library.js';
import Modal from './Modal.js';

const { toRefs, ref, onMounted, reactive, nextTick } = Vue;

export default {
	emits: ['send-message'],
	props: ['messageForm'],
	components: {
		Modal,
	},
	setup(props, { emit }) {
		const { messageForm } = toRefs(props);
		const isUploading = ref(false);

		const form = reactive({
			content: '',
			attachments: []
		});

		const dropzoneRef = ref('');

		const dropzoneVisibility = ref(false);
		
		const showDropzone = () => {
			form.content = messageForm.value.content;
			dropzoneVisibility.value = true;
			let objDZ = Dropzone.forElement("#send-attachments");
			objDZ.emit("resetFiles");

			nextTick(() => {
				dropzoneRef.value.classList.remove('dz-started');
			});
		}

		const hideDropzone = () => {
			dropzoneVisibility.value = false;
		}

		const success = (file, s) => {
			const model = s.file;
			model.uploadToken = file.upload.uuid;

			form.attachments.push(model);
        	
			// hideAppLoading('.chat-modal');
		}

		const removedfile = (file) => {
			const newArray = form.attachments.filter(s => s.uploadToken != file.upload.uuid);
			form.attachments = newArray;
		}

		const sendMessage = () => {
			if (isUploading.value == false) {
				const content = form.content,
					attachments = form.attachments.map(file => file.token);

				emit('send-message', {content, attachments});

				form.content = '';
				form.attachments = [];

				dropzoneVisibility.value = false;
			}
		}

		const initDropzone = () => {
			$('#send-attachments').dropzone({
		        url: chatModule.fileUploadUrl, // Set the url for your upload script location
		        paramName: "UploadForm[fileInput]", // The name that will be used to transfer the file
		        maxFiles: 10,
		        maxFilesize: 10, // MB
		        addRemoveLinks: true,
		        dictRemoveFileConfirmation: 'Remove File ?',
		        dictRemoveFile: 'Remove',
		        acceptedFiles: chatModule.acceptedFiles.join(','),
		        init: function() {
		            
		            this.on("sending", function(file, xhr, formData) {
		            	isUploading.value = true;
		            	// showAppLoading('.chat-modal', 'File uploading...');
		                // {$sending}
		                // let parameters = {$parameters};
		                // for ( let key in parameters ) {
		                //     formData.append(key, parameters[key]);
		                // }
		                formData.append('UploadForm[modelName]', 'SpaceMessage');
		                formData.append('UploadForm[token]', file.upload.uuid);
		                formData.append(chatModule.csrfParam, chatModule.csrfToken);
		                // formData.append('UploadForm[path]', '{$path}');
		            });
		            this.on('removedfile', removedfile);
		            this.on('complete', function (file) {
		                isUploading.value = false;
		            });
		            this.on('success', success);
		            this.on('resetFiles', function() {
				        if(this.files.length != 0){
				            for(let i=0; i<this.files.length; i++){
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

		const handleShiftEnter = () => {
			form.content += '\n';
		}

		return {
			dropzoneVisibility,
			showDropzone,
			hideDropzone,
			sendMessage,
			form,
			dropzoneRef,
			handleShiftEnter,
			isUploading
		}
	},

  	template: `
		<span>
			<button @click="showDropzone" type="button" class="btn btn-secondary btn-lg text-uppercase font-weight-bold btn-attach-file">
                Add File
            </button>

            <modal v-show="dropzoneVisibility" class="chat-modal">
		        <template v-slot:header>
		            <h3> Send Attachments </h3>
		        </template>

		        <template v-slot:body>
		            <div class="dropzone dropzone-default dropzone-primary" id="send-attachments" ref="dropzoneRef">
                        <div class="dropzone-msg dz-message needsclick">
                            <h3 class="dropzone-msg-title">
                                Drop files or click to upload
                            </h3>
                            <span class="dropzone-msg-desc">
                               You can upload 10 files here.
                            </span>
                        </div>
                    </div>
                    <div class="mt-5"></div>
                    <textarea class="form-control" rows="5" v-model="form.content"></textarea>
		        </template>

		        <template v-slot:footer>
		        	<button v-if="!isUploading" type="button" class="btn btn-primary text-uppercase font-weight-bold" @click="sendMessage">
		                Send
		            </button>
		            <button type="button" class="btn btn-light-primary font-weight-bold" @click="hideDropzone">
		                Close
		            </button>
		        </template>
		    </modal>
		</span>
	`
}