
document.getElementById( "client-dashboard-search" ).addEventListener( "input", function(e) {
	let plugins = document.querySelectorAll('[data-plugin]');
	plugins.forEach(function(currentValue){
		let p = currentValue.getAttribute('data-plugin').replace( '.php', '' ).replace( '/', ' ' ).replace( '-', ' ' );
		let q = currentValue.querySelector('.client-dashboard__name').textContent.toLowerCase().trim();

		if( q.includes( e.target.value.toLowerCase() ) ) {
			currentValue.removeAttribute('hidden');
			currentValue.closest( 'details' ).setAttribute('open',true);
		}
		else {
			currentValue.setAttribute('hidden',true);
		}

		if( '' == e.target.value ) {
			currentValue.closest( 'details' ).removeAttribute('open');
		}
	});
});

document.addEventListener( "click", function(e) {
	if( e.target && e.target.matches( ".client-dashboard__update-button" ) ) {
		let slug = e.target.dataset.slug;
		let version = e.target.dataset.version;

		let json = JSON.parse( e.target.closest( '.client-dashboard__table' ).dataset.json );
		let url = 'https://' + json.host + '/wp-json/client-dashboard/v1/plugin/update';
		let key = json.key;

		let httpRequest = new XMLHttpRequest();

		if( ! httpRequest ) {
			alert( "Giving up :( Cannot create an XMLHTTP instance" );
			return false;
		}

		httpRequest.onreadystatechange = alertContents;
		httpRequest.open("POST", url);
		httpRequest.setRequestHeader("key", key);
		httpRequest.setRequestHeader("slug", slug);
		httpRequest.setRequestHeader("version", version);
		httpRequest.send();

		function alertContents() {
			if( httpRequest.readyState === XMLHttpRequest.DONE ) {
				if( httpRequest.status === 200 ) {

					let response = JSON.parse( httpRequest.responseText );

					const el1 = document.querySelector('[data-slug="'+response.slug+'"]');
					const el2 = el1.parentElement.parentElement.querySelector(".client-dashboard__version");

					el1.parentElement.innerHTML='';
					el2.innerHTML = response.version;

				} else {
					console.log("There was a problem with the request.");
				}
			}
		}
	}
});
