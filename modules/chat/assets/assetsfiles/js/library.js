const { ref } = Vue;

const token = document.querySelector('#chat-module').getAttribute('data-token');

const appState = ref({
	isLoading: true
});

const request = ( url, params = {}, method = 'GET', signal) => {
	url = chatModule.baseUrl + url;
	params[chatModule.csrfParam] = chatModule.csrfToken

    let options = {
        method,
    };

    if (signal) {
    	options.signal = signal;
    }

    if ( 'GET' === method ) {
        url += '?' + ( new URLSearchParams( params ) ).toString();
    } else {
        options.body = JSON.stringify(params);
        options.headers = {
        	'Content-Type': 'application/json'
        }
    }
    
    return fetch( url, options ).then( response => response.json() );
};
const get = ( url, params, signal='' ) => request( url, params, 'GET', signal );
const post = ( url, params, signal='' ) => request( url, params, 'POST', signal );

const sweetAlert = (title='Title', icon='success', timer=1000) => {
	Swal.fire({
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: timer
    });
}

const spaceType = {
	private: 0,
	public: 1,
	personal: 2,
};

const showAppLoading = (el, message='Loading...', callback='') => {
	callback = callback || (() => {
		appState.value.isLoading = true;
	});
	callback();

	KTApp.block(el, {
		overlayColor: '#000000',
		message: message,
		state: 'primary'
	});

}
const hideAppLoading = (el, callback='') => {
	callback = callback || (() => {
		appState.value.isLoading = false;
	});
	callback();
	KTApp.unblock(el);
}

const isObjectEmpty = (obj) =>  {
	for(var prop in obj) {
		if(Object.prototype.hasOwnProperty.call(obj, prop)) {
			return false;
		}
	}
	return JSON.stringify(obj) === JSON.stringify({});
}

const truncateString = (string = '', maxLength = 50) => {
  return string.length > maxLength 
    ? `${string.substring(0, maxLength)}…`
    : string
}

export {
	appState,
	get,
	post,
	sweetAlert,
	token,
	spaceType,
	showAppLoading,
	hideAppLoading,
	isObjectEmpty,
	truncateString
}