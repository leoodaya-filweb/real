import { post, get, token, showAppLoading, hideAppLoading, isObjectEmpty, appState, truncateString } from './library.js';
import MessageContainerHeader from './components/MessageContainerHeader.js';
import SpacesContainer from './components/SpacesContainer.js';
import SpaceMessageList from './components/SpaceMessageList.js';
import AttachmentButton from './components/AttachmentButton.js';
import AmbulanceRequestForm from './components/AmbulanceRequestForm.js';

const { reactive, ref, createApp, onMounted, nextTick, computed } = Vue;

const chat = createApp({
	components: {
		MessageContainerHeader,
		SpacesContainer,
		SpaceMessageList,
		AttachmentButton,
		AmbulanceRequestForm,
	},
	setup() {
		const controller = ref(new AbortController());

		const spaceToken = ref(token);
		const messageFormState = reactive({
			isSending: false,
			content: []
		});
		const spaceMessagesTotal = ref(0);
		const messageForm = ref({'content': ''});
		const spaces = ref([]);
		const activeSpace = ref({});
		const currentUser = ref({});
		const searchSpace = ref('');
		const availableUsers = ref([]);
		const spaceMessages = ref([]);
		const spaceGroups = ref([]);
		const users = ref([]);
		const conversationsContainer = ref('');
		const showScrollable = ref(false);




		const setTitle = () => {
			window.document.title = 'Community Board: ' + activeSpace.value.name;
			window.history.pushState('space', '', `${chatModule.baseUrl}${spaceToken.value}`);
		}

		const leaveSpace = (response) => {
			controller.value.abort('State Changed');
			activeSpace.value = {};
			spaceMessages.value = [];
			spaceGroups.value = [];
			spaces.value = response.spaces;

			window.document.title = 'Community Board';
			window.history.pushState('space', '', `${chatModule.baseUrl}`);
			poll(new AbortController());
		}

		const saveNewSpace = (response) => {
			controller.value.abort('State Changed');
			activeSpace.value = response.activeSpace;
			spaceToken.value = response.activeSpace.token;
			spaces.value = response.spaces;
			reloadActiveSpace();
			setTitle();
		}
		const reloadActiveSpace = () => {
			showAppLoading('.message-main-container', 'Loading Messages...');
			spaceMessages.value = [];
			get('default/view-space', {token: spaceToken.value})
		    .then(response => {
				activeSpace.value = response.space;
				spaceMessages.value = response.spaceMessages;
				spaceGroups.value = response.spaceGroups;
				availableUsers.value = response.availableUsers;
				spaces.value = response.spaces;
				hideAppLoading('.message-main-container');
				
		  		scrollToBottom();
				poll(new AbortController());
		    })
		    .catch(e => {
                console.log(e)
				hideAppLoading('.message-main-container',);
            });
		}
		const selectSpace = (space) => {
			controller.value.abort('State Changed');
			showScrollable.value = false;

			spaceToken.value = space.token;
			activeSpace.value = space;
			reloadActiveSpace();
			setTitle();
		}
		const removeMember = (response) => {
			controller.value.abort('State Changed');
			spaceGroups.value = response.spaceGroups;
			availableUsers.value = response.availableUsers;
			activeSpace.value = response.activeSpace;
			poll(new AbortController());
		}
		const saveMember = (response) => {
			controller.value.abort('State Changed');
			spaceGroups.value = response.spaceGroups;
			availableUsers.value = response.availableUsers;
			activeSpace.value = response.activeSpace;
			poll(new AbortController());
		}
		const saveActiveSpace = (response) => {
			controller.value.abort('State Changed');
			spaceGroups.value = response.spaceGroups;
			availableUsers.value = response.availableUsers;
			activeSpace.value = response.activeSpace;
			spaces.value = response.spaces;
			spaceToken.value = response.activeSpace.token;
			setTitle();
			poll(new AbortController());
		}

		const refreshData = (response) => {
			if ("activeSpace" in response) {
				activeSpace.value = response.activeSpace || {};
			}

			if ("availableUsers" in response) {
				availableUsers.value = response.availableUsers || [];
			}

			if ("spaceGroups" in response) {
				spaceGroups.value = response.spaceGroups || [];
			}

			if ("spaceMessages" in response) {
				let sm = spaceMessages.value.concat(response.spaceMessages);
				spaceMessages.value = sm;
			}

			if ("users" in response) {
				users.value = response.users || [];
			}

			if ("spaces" in response) {
				spaces.value = response.spaces || [];
			}
			if ("currentUser" in response) {
				currentUser.value = response.currentUser || {};
			}
			if ("spaceMessagesTotal" in response) {
				spaceMessagesTotal.value = response.spaceMessagesTotal || 0;
			}

	  		scrollToBottom(false);
		}
		const initData = () => {
			showAppLoading('#chat-module', 'Initializing Data...');
			get('default/init-data', {token: spaceToken.value})
		    .then(response => {
	       		activeSpace.value = response.activeSpace || {};
	       		availableUsers.value = response.availableUsers || [];
	       		spaceGroups.value = response.spaceGroups || [];
	       		spaceMessages.value = response.spaceMessages || [];
	       		users.value = response.users || [];
	       		spaces.value = response.spaces || [];
	       		currentUser.value = response.currentUser || {};
				hideAppLoading('#chat-module');

		  		scrollToBottom();
				poll();
		    })
		    .catch(e => {
                console.log(e)
				hideAppLoading('#chat-module');
            });
		}

		const poll = (newController='') => {
			controller.value = newController || controller.value;

			post('default/poll', {chatState: chatState()}, controller.value.signal)
		    .then(response => {
		       	if (response.status == 'success') {
		       		refreshData(response);
		       	}
				messageFormState.isSending = false;
				messageFormState.content = [];
		       	poll();
		    })
		    .catch(e => {
		    	console.log(e);
            });
		}

		const imageLoadedCallback = (callback) => {
			Promise.all(Array.from(document.images).filter(img => !img.complete).map(img => new Promise(resolve => { img.onload = img.onerror = resolve; }))).then(callback);
		}

		const scrollToBottom = (force = true) => {
	  		nextTick(() => {
	  			if (force) {
	  				imageLoadedCallback(() => {
	  					conversationsContainer.value.scrollTop = conversationsContainer.value.scrollHeight;

	  					// conversationsContainer.value.scrollTo({
	  					// 	top: conversationsContainer.value.scrollHeight,
	  					// 	behavior: 'smooth'
	  					// });
	  				});
	  			}
	  			else {
	  				if(conversationsContainer.value.scrollHeight - conversationsContainer.value.scrollTop <= 1000) {

	  					imageLoadedCallback(() => {
			  				conversationsContainer.value.scrollTop = conversationsContainer.value.scrollHeight;
	  					});
	  				}
	  			}
			});
		}

		const chatState = () => {
			return {
				activeSpaceTimestamp: activeSpace.value?.timestamp || 0,
				activeSpaceToken: activeSpace.value?.token || null,

				availableUsersCount: availableUsers.value.length,
				spaceGroupsCount: spaceGroups.value.length,
				spacesCount: spaces.value.length,
				// usersCount: users.value.length,

				currentUserTimestamp: currentUser.value?.timestamp || 0,

				latestSpaceTimestamp: Math.max(...spaces.value.map(space => space.timestamp)),
				maxMessageId: Math.max(...spaceMessages.value.map(spaceMessage => spaceMessage.id)),
				minMessageId: Math.min(...spaceMessages.value.map(spaceMessage => spaceMessage.id)),

				spaceMessagesTotal: spaceMessagesTotal.value
			}
		}

		const isScrollable = (ele) => {
		    // Compare the height to see if the element has scrollable content
		    const hasScrollableContent = ele.scrollHeight > ele.clientHeight;

		    // It's not enough because the element's `overflow-y` style can be set as
		    // * `hidden`
		    // * `hidden !important`
		    // In those cases, the scrollbar isn't shown
		    const overflowYStyle = window.getComputedStyle(ele).overflowY;
		    const isOverflowHidden = overflowYStyle.indexOf('hidden') !== -1;

		    return hasScrollableContent && !isOverflowHidden;
		}

		const messageScroll = (e) => {

		    if (e.target.scrollTop == 0 && isScrollable(e.target)) {
	    		const minMessageId = Math.min(...spaceMessages.value.map(spaceMessage => spaceMessage.id));
	    		const lastMessageElement = document.getElementById('message-id-' + minMessageId);

		    	if (minMessageId > activeSpace.value.minimumMessageId) {
			    	showAppLoading('.messages-body', 'Loading Messages...');
					post('default/load-previous-space-messages', {token: activeSpace.value.token, minMessageId})
				    .then(response => {
				    	if (response.status == 'success') {
				    		const sm = response.spaceMessages.concat(spaceMessages.value);
				    		spaceMessages.value = sm;

				    		nextTick(() => {
				    			conversationsContainer.value.scrollTop = lastMessageElement.offsetTop;
				    		});
				    	}

						hideAppLoading('.messages-body');
				    })
				    .catch(e => {
		                console.log(e)
						hideAppLoading('.messages-body',);
		            });
		    	}
		    }

		    if(conversationsContainer.value.scrollHeight - conversationsContainer.value.scrollTop > 1000) {
		    	showScrollable.value = true;
		    }
		    else {
		    	showScrollable.value = false;
		    }
		}

		const resetMessageForm = () => {
			messageForm.value = {
				content: ''
			}
		}

		const saveMessageWithAttachhments = ({content, attachments}) => {
			saveNewMessage(content, attachments);
		}

		const handleEnterMessage = () => {
			if(messageForm.value.content.trim() != '') {
				saveNewMessage();
			}
		}

		const saveNewMessage = (_content='', _attachments='') => {
			const content = _content ? _content: messageForm.value.content;
			const attachments = _attachments ? _attachments: [];

			if (content || attachments.length) {
				messageFormState.isSending = true;

				let contentPlaceholder = '';
				if (content) {
					if (attachments.length) {
						contentPlaceholder = 'Sending "' + truncateString(content) + '" with ' + attachments.length + ' attachments';
					}
					else {
						contentPlaceholder = truncateString(content);
					}
				}
				else {
					contentPlaceholder = 'Sending ' + attachments.length + ' attachments';
				}
				messageFormState.content.push(contentPlaceholder);

				resetMessageForm();
				scrollToBottom();

				post('default/send-new-message', {token: activeSpace.value.token, content, attachments})
			    .then(response => {
			    	if (response.status == 'success') {
			    		
			    	}
					hideAppLoading('.messages-body');
			    })
			    .catch(e => {
	                console.log(e)
					hideAppLoading('.messages-body',);
					messageFormState.isSending = false;
	            });
			}
		}

		const ambulanceRequestContainer = ref('');
		onMounted(() => {
		  	initData();
		});

		return {
			saveActiveSpace,
			availableUsers,
			saveMember,
			removeMember,
			appState,
			activeSpace,
			spaces,
			users,
			selectSpace,
			saveNewSpace,
			spaceMessages,
			spaceGroups,
			isObjectEmpty,
			conversationsContainer,
			chatState,
			currentUser,
			messageScroll,
			saveNewMessage,
			messageForm,
			messageFormState,
			saveMessageWithAttachhments,
			handleEnterMessage,
			leaveSpace,
			showScrollable,
			scrollToBottom,
			chatModule,
			ambulanceRequestContainer
		}
	}
});

chat.mount('#chat-module');


