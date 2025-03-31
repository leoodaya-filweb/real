import { appState } from '../library.js';
import MessageAttachments from './MessageAttachments.js';

const { toRefs } = Vue;

export default {
	props: ['spaceMessages', 'messageFormState', 'currentUser'],
    components: {
        MessageAttachments
    },
	setup(props, { emit }) {

		const { spaceMessages, messageFormState, currentUser } = toRefs(props);

        const showTimesent = (index) => {
            if (index == 0) {
                return true;
            }

            let currentMessage = spaceMessages.value[index];
            let previousMessage = spaceMessages.value[index - 1];

            if (previousMessage) {
                if (currentMessage.created_by == previousMessage.created_by && currentMessage.isSender == previousMessage.isSender && currentMessage.timeSent == previousMessage.timeSent) {
                    return false;
                }
            }

            return true;
        }

        const messageClass = (index) => {
            if (index == 0) {
                return 'mb-5';
            }

            let currentMessage = spaceMessages.value[index];
            let nextMessage = spaceMessages.value[index + 1];

            if (nextMessage) {
                if (currentMessage.created_by == nextMessage.created_by && currentMessage.isSender == nextMessage.isSender && currentMessage.timeSent == nextMessage.timeSent) {
                    return '';
                }
            }

            return 'mb-5';
        }

		return {
            spaceMessages,
			appState,
            messageFormState,
            currentUser,
            showTimesent,
            messageClass
		}
	},
  	template: `
        <div>
    	  	<div v-if="spaceMessages.length" class="space-message-list-container">
                <div v-for="(spaceMessage, index) in spaceMessages" :key="spaceMessage.id">
                    <div :id="'message-id-' + spaceMessage.id" class="">

                        <div v-if="spaceMessage.isTypeLabel" class="message-type-label">
                            <img :src="spaceMessage.senderPhotoLink" class="img-fluid sender-photo">
                            <div v-if="spaceMessage.formattedContent" class="ml-2 mt-2 text-muted">
                                <span class="font-weight-bold" v-html="spaceMessage.formattedContent"></span> 
                                <span class="text-uppercase"> ({{ spaceMessage.timeSent }})</span>
                            </div>
                        </div>

                        <div v-else-if="spaceMessage.isSender" class="d-flex flex-column align-items-end" :class="messageClass(index)">
                            <div v-if="showTimesent(index)" class="d-flex align-items-center">
                                <div>
                                    <span class="text-muted font-size-sm ago text-uppercase" v-text="spaceMessage.timeSent"></span>
                                    &nbsp;
                                    <a href="javascript:void(0)" class="text-dark-75 text-hover-primary font-weight-bold font-size-h7">You</a>
                                </div>
                                <div class="symbol symbol-circle symbol-30 ml-3">
                                    <img :src="spaceMessage.senderPhotoLink" class="img-fluid sender-photo">
                                </div>
                            </div>
                            
                            <message-attachments class="mr-12" :space-message="spaceMessage" add-class="message-is-sender"></message-attachments>
                            <div v-if="spaceMessage.formattedContent" class="mr-12 mb-1 rounded p-3 bg-primary text-white font-weight-bold font-size-sm text-right max-w-400px message-is-sender">
                                <span v-html="spaceMessage.formattedContent"></span>
                            </div>
                        </div>

                        <div v-else class="d-flex flex-column align-items-start" :class="messageClass(index)">
                            <div v-if="showTimesent(index)" class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-30 mr-3">
                                    <img :src="spaceMessage.senderPhotoLink" class="img-fluid sender-photo">
                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="text-dark-75 text-hover-primary font-weight-bold font-size-h7" v-text="spaceMessage.senderName"> </a>
                                    &nbsp;
                                    <span class="text-muted font-size-sm text-uppercase" v-text="spaceMessage.timeSent"></span>
                                </div>
                            </div>
                            <message-attachments add-class="message-is-receiver" class="ml-12" :space-message="spaceMessage"></message-attachments>
                            <div v-if="spaceMessage.formattedContent" class="ml-12 mb-1 rounded p-3 bg-light-primary text-dark-50 font-weight-bold font-size-sm text-left max-w-400px message-is-receiver">
                                <span v-html="spaceMessage.formattedContent"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="messageFormState.content.length">
                    <div v-for="(content, index) in messageFormState.content" :key="index" class="d-flex flex-column mb-5 align-items-end">
                        <div class="d-flex align-items-center">
                            <div>
                                <span class="text-muted font-size-sm ago"> Sending... </span>
                            </div>
                            <div class="symbol symbol-circle symbol-30 ml-3">
                                <img :src="currentUser.photoLink" class="img-fluid sender-photo">
                            </div>
                        </div>
                        <div class="mr-12 mt-2 rounded p-3 bg-light-primary text-dark-50 font-weight-bold font-size-sm text-right max-w-400px message-is-sender">
                            <span v-html="content"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                {{ appState.isLoading ? 'Loading': 'No messages' }}
            </div>
        </div>
	`
}