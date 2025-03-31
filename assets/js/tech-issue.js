import AttachmentButton from './AttachmentButton.js';
import { onValue, ref as firebaseRef , push, set} from "https://www.gstatic.com/firebasejs/9.23.0/firebase-database.js";
import {database} from '../firebase//config.js';

const { reactive, ref, createApp, onMounted, nextTick, computed } = Vue;
const elementSelector = '#tech-issue-page';
const token = document.querySelector(elementSelector).getAttribute('data-token');
const addedLog = document.querySelector(elementSelector).getAttribute('data-added_log');
console.log(addedLog)
const techIssue = createApp({
	components: {
		AttachmentButton,
	},
	setup() {
		const logs = ref([]);
		const logRefs = ref([]);
		const currentUser = ref({});
		const activeTechIssue = ref({});
		const showScrollable = ref(false);

		const totalLogs = ref(0);
		const messageForm = ref({'content': ''});
		const messageFormState = reactive({
			isSending: false,
			content: []
		});
		const conversationsContainer = ref('');

		const showAppLoading = (message='Loading...', el='') => {
			el = el ? el: elementSelector;
			KTApp.block(el, {
				overlayColor: '#000000',
				message: message,
				state: 'primary'
			});
		}

		const hideAppLoading = (el='') => {
			el = el ? el: elementSelector;
			KTApp.unblock(el);
		}

		const truncateString = (string = '', maxLength = 50) => {
		  return string.length > maxLength 
		    ? `${string.substring(0, maxLength)}…`
		    : string
		}

		const initData = () => {
			showAppLoading('Initializing Data...');
			$.ajax({
				url: app.baseUrl + 'tech-issue/view',
				data: {token, response: 'json'},
				method: 'get',
				dataType: 'json',
				success: (s) => {
					logs.value = s.logs || [];
		       		totalLogs.value = s.totalLogs || 0;
		       		currentUser.value = s.currentUser || {};
		       		activeTechIssue.value = s.activeTechIssue || {};

					hideAppLoading();

			  		scrollToBottom();

			  		if (addedLog) {
			  			firebasePush()
			  		}

					watchLogs();
				},
				error: (e) => {
					hideAppLoading();
				}
			});
		}

		const watchLogs = () => {
			onValue(firebaseRef(database, 'tech-issue/' + token), function(snapshot) {
			  	if(snapshot) {
			  		poll();
			  	}
			});
		}

		const chatState = () => {
			return {
				maxLogId: Math.max(...logs.value.map(log => log.id)),
				totalLogs: totalLogs.value,
				token
			}
		}

		const resetFormSpaceState = () => {
			messageFormState.isSending = false;
			messageFormState.content = [];
		}

		const firebasePush = () => {
			set(firebaseRef(database, 'tech-issue/' + token), (new Date()).getTime())
	        .catch((error) => {
	          console.error("Error setting data:", error);
	        });
		}

		const poll = () => {
			$.ajax({
				url: app.baseUrl + 'tech-issue/poll-logs',
				data: chatState(),
				method: 'post',
				dataType: 'json',
				success: (s) => {
					if (s.status == 'success') {
			       		if ("totalLogs" in s) {
							totalLogs.value = s.totalLogs || 0;
							scrollToBottom(false);
						}

						if ("logs" in s) {
							let sm = logs.value.concat(s.logs);
							logs.value = sm;
							scrollToBottom(false);
						}
			       	}
			       	resetFormSpaceState();
			       	// poll();
				},
				error: (e) => {
	                // Swal.fire('Error', e.responseText, 'error');
				}
			});
		}

		const imageLoadedCallback = (callback) => {
			Promise.all(Array.from(document.images).filter(img => !img.complete).map(img => new Promise(resolve => { img.onload = img.onerror = resolve; }))).then(callback);
		}

		const scrollToBottom = (force = true) => {
	  		nextTick(() => {
	  			if (force) {
  					conversationsContainer.value.scrollTop = conversationsContainer.value.scrollHeight;
	  			}
	  			else {
	  				if(conversationsContainer.value.scrollHeight - conversationsContainer.value.scrollTop <= 1000) {
	  					conversationsContainer.value.scrollTo({
	  						top: conversationsContainer.value.scrollHeight,
	  						behavior: 'smooth'
	  					});
	  				}
	  			}
			});
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
	    		const minLogId = Math.min(...logs.value.map(log => log.id));
				

		    	if (minLogId > activeTechIssue.value.minimumLogId) {
			    	showAppLoading('Loading Logs...', '.timeline');

			    	$.ajax({
			    		url: app.baseUrl + 'tech-issue/load-previous-logs',
			    		data: { token, minLogId },
			    		method: 'post',
			    		dataType: 'json',
			    		success: (s) => {
			    			if (s.status == 'success') {
					    		const sm = s.logs.concat(logs.value);
					    		logs.value = sm;

					    		nextTick(() => {

					    			const lastLogElement = logRefs.value.find((el) => el.getAttribute('id') == 'log-id-' + minLogId);
					    			conversationsContainer.value.scrollTop = lastLogElement.offsetParent.offsetTop;
					    		});
					    	}

							hideAppLoading('.timeline');
			    		},
			    		error: (e) => {
			    			Swal.fire('Error', e.responseText, 'error');
			    			hideAppLoading('.timeline');
			    		}
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

				$.ajax({
					url: app.baseUrl + 'tech-issue/add-new-log',
					data: { token, content, attachments },
					method: 'post',
					dataType: 'json',
					success: (s) => {
						if (s.status == 'success') {
							firebasePush();
						}
					},
					error: (e) => {
		                Swal.fire('Error', e.responseText, 'error');
			       		resetFormSpaceState();
					}
				});
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

		onMounted(() => {
		  	initData();
		});

		return {
			logs,
			conversationsContainer,
			messageForm,
			handleEnterMessage,
			saveNewMessage,
			saveMessageWithAttachhments,
			messageFormState,
			currentUser,
			messageScroll,
			logRefs,
			showScrollable,
			scrollToBottom
		}
	}
});

techIssue.mount(elementSelector);


