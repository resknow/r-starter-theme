class GoogleMap extends HTMLElement {
	connectedCallback() {
		// Config
		this.config = {
			location: {
				lat: this.getAttribute('lat'),
				lng: this.getAttribute('lng'),
			},
			zoom: parseInt(this.getAttribute('zoom')) || 12,
		};

		document.addEventListener(
			'DOMContentLoaded',
			this.initGoogleMaps.bind(this)
		);
	}

	initGoogleMaps() {
		this.canvas = document.createElement('div');
		this.canvas.classList.add('map-canvas');
		this.appendChild(this.canvas);

		google.maps.event.addDomListener(
			window,
			'load',
			this.setupGoogleMap.bind(this)
		);
	}

	setupGoogleMap() {
		let position = new google.maps.LatLng(
			this.config.location.lat,
			this.config.location.lng
		);

		// Add the coordinates
		let mapOptions = {
			zoom: this.config.zoom,
			minZoom: 6,
			maxZoom: 17,
			draggable: true,
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.DEFAULT,
			},
			center: position,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: false,

			// All of the below are set to true by default, so simply remove if set to true:
			panControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,
			rotateControl: false,
		};

		let map = new google.maps.Map(this.canvas, mapOptions);

		let marker = new google.maps.Marker({
			position: position,
			map: map,
		});

		google.maps.event.addDomListener(window, 'resize', function () {
			map.setCenter(position);
		});
	}
}

window.customElements.define('google-map', GoogleMap);
